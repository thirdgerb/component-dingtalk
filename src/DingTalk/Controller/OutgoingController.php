<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\DingTalk\Controller;

use Commune\Blueprint\Host;
use Commune\Blueprint\Shell;
use Commune\Contracts\Log\ExceptionReporter;
use Commune\DingTalk\DingTalk\Handler\OutgoingHandler;
use Commune\DingTalk\Configs\BotConfig;
use Commune\DingTalk\Contracts\BotManager;
use Commune\DingTalk\Messages\Incoming\DTEmpty;
use Commune\DingTalk\Messages\Incoming\DTText;
use Commune\DingTalk\Messages\Outgoing\OutgoingMessage;
use Commune\Blueprint\Framework\ReqContainer;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponse;
use Psr\Log\LoggerInterface;


/**
 * 接受 钉钉群 Outgoing 消息的控制器.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class OutgoingController
{

    /**
     * @var Host
     */
    protected $host;

    /**
     * @var Shell
     */
    protected $shell;

    /**
     * OutgoingController constructor.
     * @param Host $host
     */
    public function __construct(Host $host)
    {
        $this->host = $host;
        $this->shell = $host->getProcContainer()->make(Shell::class);
    }


    /**
     * 接受到来自 ding talk 的 outgoing 消息.
     * 本方法是控制流程.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return PsrResponse
     */
    public function onReceive(
        RequestInterface $request,
        ResponseInterface $response
    ) : PsrResponse
    {

        try {

            $outgoingMessage = $this->makeOutgoingMessage($request);
            if (empty($outgoingMessage)) {
                return $this->emptyResponse($response);
            }

            // 创建请求级容器, 实现请求隔离.
            $container = $this->shell->newReqContainerIns($outgoingMessage->msgId);

            /**
             * 获取 group
             * @var BotManager $manager
             */
            $manager = $container->make(BotManager::class);
            $uri = $request->getRequestUri();
            $group = $manager->findBotByUri($uri);

            // 如果分组不存在, 则是重大异常.
            // 理论上能命中控制器, 就应该正确定义过
            if (empty($group)) {
                /**
                 * @var LoggerInterface $logger
                 */
                $logger = $container->make(LoggerInterface::class);
                $logger->error(
                    static::class . '::' . __FUNCTION__
                    . " can not find ding talk group by url $uri "
                );

                // 尽量不给钉钉返回异常信息
                return $this->emptyResponse($response);
            }


            // 校验请求签名是否正确.
            $invalidResponse = $this->validateSignature(
                $group,
                $request,
                $response
            );
            if (isset($invalidResponse)) {
                return $invalidResponse;
            }

            // 绑定已有的元素.
            $container->instance(RequestInterface::class, $request);
            $container->instance(ResponseInterface::class, $response);
            $container->instance(BotConfig::class, $group);

            return $this->handleRequest(
                $container,
                $group,
                $request,
                $response,
                $outgoingMessage
            );

        } catch (\Throwable $e) {

            $this->logException($e);

            $type = get_class($e);
            $message = $e->getMessage();
            $text = DTText::instance("unexpected error: $type, $message");

            return $response->json($text->toDingTalkData());
        }
    }

    /**
     * 校验请求签名.
     * @param BotConfig $group
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return null|PsrResponse
     */
    protected function validateSignature(
        BotConfig $group,
        RequestInterface $request,
        ResponseInterface $response
    ) : ? PsrResponse
    {
        $microTimestamp = $request->getHeaderLine('timestamp');
        $sign = $request->getHeaderLine('sign');
        $now = intval(microtime(true) * 1000) + 1;

        if (empty($microTimestamp) || empty($sign)) {
            return $this->emptyResponse($response);
        }

        $microTimestamp = intval($microTimestamp);

        // 时间间隔超过 1 小时.
        $expect = '';
        $passed = $now - $microTimestamp;
        if ($passed > 0 && $passed < 3600000) {
            $secret = $group->appSecret;
            $stringToSign = utf8_encode($microTimestamp . "\n" . $secret);
            $stringToSignBytes = array_map('ord', str_split($stringToSign));
            $secretBytes = array_map('ord', str_split(utf8_encode($secret)));
            $stringToSignEn = pack('C*', ...$stringToSignBytes);
            $secretEn = pack('C*', ...$secretBytes);
            $hashed = hash_hmac('sha256',  $stringToSignEn, $secretEn, true);
            $expect = base64_encode($hashed);


            if ($sign === $expect) {
                return null;
            }

        }

        $this->logDebug("invalid ding talk outgoing message. timestamp $microTimestamp, sign $sign, expect $expect");
        return $this->emptyResponse($response);
    }

    /**
     * 记录异常
     * @param \Throwable $e
     */
    protected function logException(\Throwable $e) : void
    {
        /**
         * @var ExceptionReporter $reporter
         */
        $reporter = $this->shell->getProcContainer()->make(ExceptionReporter::class);
        $reporter->report($e);
    }

    /**
     * 记录 debug 日志
     * @param string $message
     */
    protected function logDebug(string $message) : void
    {
        /**
         * @var LoggerInterface $logger
         */
        $logger = $this->shell->getProcContainer()->make(LoggerInterface::class);
        $logger->debug(static::class . ' ' . $message);
    }


    /**
     * 从请求中获取 OutgoingMessage
     * @return null|OutgoingMessage
     */
    protected function makeOutgoingMessage(
        RequestInterface $request
    ) : ? OutgoingMessage
    {
        $content = $request->getBody()->getContents();
        $this->logDebug("incoming message: $content");

        // 检查 body
        $data = json_decode($content, true);
        if (!is_array($data)) {
            $this->logDebug("invalid request content: $content");
            return null;
        }

        try {
            // 包装数据的同时完成参数校验
            return new OutgoingMessage($data);
        } catch (\Throwable $e) {
            $this->logException($e);
            return null;
        }
    }

    /**
     * @param ReqContainer $container
     * @param BotConfig $group
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param OutgoingMessage $message
     * @return PsrResponse
     */
    protected function handleRequest(
        ReqContainer $container,
        BotConfig $group,
        RequestInterface $request,
        ResponseInterface $response,
        OutgoingMessage $message
    ) : PsrResponse
    {
        /**
         * @var OutgoingHandler $handler
         */
        $outgoingHandlerName = $group->outgoingHandler;
        $handler = $container->make($outgoingHandlerName);

        return $handler(
            $container,
            $group,
            $request,
            $response,
            $message
        );
    }

    /**
     * @param ResponseInterface $response
     * @return PsrResponse
     */
    protected function emptyResponse(ResponseInterface $response) : PsrResponse
    {
        $dtMsg = new DTEmpty;
        return $response->json($dtMsg->toDingTalkData());
    }
}