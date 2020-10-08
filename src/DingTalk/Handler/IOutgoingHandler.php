<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\DingTalk\Handler;

use Commune\Blueprint\Ghost\Cloner\ClonerEnv;
use Commune\Blueprint\Ghost\Cloner\ClonerGuest;
use Commune\Blueprint\Kernel\Protocals\AppResponse;
use Commune\Blueprint\Kernel\Protocals\ShellInputRequest;
use Commune\Blueprint\Shell;
use Commune\Blueprint\Framework\ReqContainer;
use Commune\Blueprint\Kernel\Handlers\ShellInputReqHandler;
use Commune\Blueprint\Kernel\Protocals\ShellOutputResponse;
use Commune\DingTalk\Messages\Incoming\DTEmpty;
use Commune\DingTalk\Messages\Incoming\DTMarkdown;
use Commune\DingTalk\Messages\Incoming\DTMessage;
use Commune\DingTalk\Messages\Outgoing\OutgoingMessage;
use Commune\Kernel\Protocals\IShellInputRequest;
use Commune\Protocals\HostMsg\Convo\VerbalMsg;
use Commune\Support\Uuid\HasIdGenerator;
use Commune\Support\Uuid\IdGeneratorHelper;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Commune\DingTalk\Configs\BotConfig;
use Psr\Http\Message\ResponseInterface as PsrResponse;
use Psr\Log\LoggerInterface;

/**
 * 用于响应 ding talk 的 outgoing 机器人消息.
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class IOutgoingHandler implements HasIdGenerator
{
    use IdGeneratorHelper;

    /**
     * @var Shell
     */
    protected $shell;

    /**
     * IOutgoingHandler constructor.
     * @param Shell $shell
     */
    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }


    /**
     * @param ReqContainer $container
     * @param BotConfig $group
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param OutgoingMessage $message
     * @return PsrResponse
     */
    public function __invoke(
        ReqContainer $container,
        BotConfig $group,
        RequestInterface $request,
        ResponseInterface $response,
        OutgoingMessage $message
    ) : PsrResponse
    {
        $traceId = $container->getId();
        $appRequest = $this->wrapRequest($traceId, $group, $message);
        $appResponse = $this->handleRequest($appRequest);

        // 检查响应是不是有问题.
        $errCode = $appResponse->getErrcode();

        if ($errCode === AppResponse::SUCCESS) {
            // 如果成功, 直接渲染返回结果.
            return $this->renderResponse($message, $response, $appResponse);
        }

        $errMsg = $appResponse->getErrmsg();
        /**
         * @var LoggerInterface $logger
         */
        $logger = $container->make(LoggerInterface::class);
        $logger->warning(
            static::class . '::' . __FUNCTION__
            . " invalid response code $errCode, message $errMsg"
        );

        $emptyMessage = new DTEmpty;
        return $response->json($emptyMessage->toDingTalkData());
    }


    /**
     * 处理一个 Request
     * @param ShellInputRequest $request
     * @param ReqContainer|null $container
     * @return ShellOutputResponse
     */
    protected function handleRequest(
        ShellInputRequest $request,
        ReqContainer $container = null
    ) : ShellOutputResponse
    {
        /**
         * @var ShellOutputResponse $response
         */
        $response = $this->shell->handleRequest(
            $request,
            ShellInputReqHandler::class,
            $container
        );
        return $response;
    }

    /**
     * 将 Http 请求包装为 Ghost 请求
     * @param string $traceId
     * @param BotConfig $group
     * @param OutgoingMessage $message
     * @return ShellInputRequest
     */
    protected function wrapRequest(
        string $traceId,
        BotConfig $group,
        OutgoingMessage $message
    ) : ShellInputRequest
    {
        $input = $message->toInputMessage($group);
        $env = $this->wrapEnv($group, $message);
        $entry = $message->isGroupConversation()
            ? $group->entry
            : $group->privateEntry;

        return IShellInputRequest::instance(
            false,
            $input,
            $entry,
            $env,
            null,
            $traceId
        );
    }

    /**
     * 包装环境变量.
     * @param BotConfig $config
     * @param OutgoingMessage $message
     * @return array
     */
    protected function wrapEnv(
        BotConfig $config,
        OutgoingMessage $message
    ) : array
    {
        $userLevel = $this->getUserLevel($config, $message);
        return [
            ClonerEnv::BOT_NAME_KEY => $config->botName,
            ClonerEnv::BOT_ID_KEY => $message->chatbotUserId,
            ClonerEnv::USER_LEVEL_KEY => $userLevel,
            ClonerEnv::USER_INFO_KEY => [
                "senderCorpId"=> $message->senderCorpId,
                "senderStaffId"=> $message->senderStaffId,
            ],
            ClonerEnv::CLIENT_INFO => [
                "conversationType"=> $message->conversationType,
                "conversationId"=> $message->conversationId,
                "conversationTitle"=> $message->conversationTitle,
            ]
        ];
    }

    /**
     * 获取用户的级别.
     * @param BotConfig $config
     * @param OutgoingMessage $message
     * @return int
     */
    protected function getUserLevel(
        BotConfig $config,
        OutgoingMessage $message
    ) : int
    {
        $userId = $message->senderId;
        $level = $config->userLevelMap[$userId] ?? ClonerGuest::GUEST;
        return $level;
    }

    /**
     * 渲染输出的响应.
     * @param OutgoingMessage $outgoing
     * @param ResponseInterface $response
     * @param ShellOutputResponse $appResponse
     * @return PsrResponse
     */
    protected function renderResponse(
        OutgoingMessage $outgoing,
        ResponseInterface $response,
        ShellOutputResponse $appResponse
    ) : PsrResponse
    {
        $outputs = $appResponse->getOutputs();

        $rendering = [];
        foreach ($outputs as $output) {
            $message = $output->getMessage();
            // 钉钉暂时也不支持别的消息. 所以返回消息也只留 verbal
            if ($message instanceof DTMessage || $message instanceof VerbalMsg) {
                $rendering[] = $message;
            }
        }

        // 只有一条 dt message 就直接发送了.
        $count = count($rendering);
        $first = $rendering[0];
        if ($count === 1 && $first instanceof DTMessage) {
            return $response->json($first->toDingTalkData());
        }

        // 否则就拼成一个 markdown
        $rendering = array_map(function(VerbalMsg $message) {
            return $message->getText();
        }, $rendering);

        $outputText = implode("\n\n----\n\n", $rendering);
        $senderNick = $outgoing->senderNick;
        $prefix = $outgoing->isGroupConversation() ? "to $senderNick: \n" : '';

        $markdown = DTMarkdown::instance(
            '',
            $prefix . $outputText
        );

        return $response->json($markdown->toDingTalkData());
    }

}