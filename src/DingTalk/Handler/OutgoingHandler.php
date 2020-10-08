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

use Commune\Blueprint\Framework\ReqContainer;
use Commune\DingTalk\Messages\Outgoing\OutgoingMessage;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Commune\DingTalk\Configs\BotConfig;
use Psr\Http\Message\ResponseInterface as PsrResponse;


/**
 * 用于响应 ding talk 的 outgoing 机器人消息.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface OutgoingHandler
{

    /**
     * 响应一个请求.
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
    ) : PsrResponse;

}