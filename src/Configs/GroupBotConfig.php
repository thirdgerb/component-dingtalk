<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Configs;


use Commune\Blueprint\Framework\Auth\Supervise;
use Commune\Blueprint\Ghost\Cloner\ClonerGuest;
use Commune\DingTalk\DingTalk\Handler\IOutgoingHandler;
use Commune\Support\Option\AbsOption;

/**
 * 钉钉群机器人的配置.
 *
 * 一个群机器人, 实际上由以下几个部分组成:
 * 1. 接受 DingTalk Outgoing message, 并且同步响应.
 * 2. 将异步消息主动推送给 ding talk 的群会话.
 * 3. 接受全局的事件通知, 决定自己的响应策略.
 * 4. 接受 DingTalk 的回调事件, 并且决定如何响应. 通常不设置这种, 因为 DingTalk 一个企业只支持一个回调链接. 被机器人占用了就不太必要.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * ## 机器人的基础数据
 *
 * @property string $id         机器人对于 Commune 项目而言的 id, 也用于场景
 * @property string $botName    机器人的名称. 可以不填.
 * 
 * ## 钉钉内部的配置.
 *
 * 注意这些参数用 env() 之类的方式获取, 不能进入版本控制以泄露
 *
 * @property int $agentId       钉钉设置机器人的 agentId
 * @property string $appKey     钉钉设置机器人的 appKey
 * @property string $appSecret  钉钉设置机器人的 appSecret
 *
 * ## 机器人对接钉钉的配置
 *
 * @property string $url 提交给钉钉的 outgoing url (域名后的相对地址), 钉钉的消息会推送到这个 url
 * @property string $outgoingHandler 接受到钉钉群 Outgoing 消息时使用的 handler
 *
 * ## 机器人的多轮对话入口
 *
 * @property string $entry 机器人的群聊 context 入口.
 * @property string $privateEntry 机器人的私聊 context 入口
 * @property int $userLevel
 *
 *

 * ## 异步响应逻辑
 *
 * @property string|null $dingTalkWebHook 群的回调机器人web hook, 用于异步推送消息给群
 * @property string[] $communeEventHandlers 群监听的 Commune 事件, 接受到这些事件后, 用 handler 进行处理.
 * @property string[] $dingTalkEventHandlers 群监听的钉钉事件, 接受到这些事件后, 用 handler 进行处理.
 *
 *
 * ## 其它逻辑
 *
 * @property string $sessionIdSalt 如果希望对会话的 sessionId 进行特殊加密, 则可加点 salt. 一般情况下 sessionId 不需要特别加密.
 *
 *
 *
 */
class GroupBotConfig extends AbsOption
{
    const IDENTITY = 'id';

    /**
     * @var string|null
     */
    protected $sessionId;

    public static function stub(): array
    {
        return [
            'id' => '',
            'botName' => '',
            'agentId' => 0,
            'appKey' => '',
            'appSecret' => '',

            'entry' => '',
            'privateEntry' => '',
            'userLevel' => ClonerGuest::GUEST,

            'url' => '',
            'outgoingHandler' => IOutgoingHandler::class,


            'dingTalkWebHook' => null,
            'communeEventHandlers' => [
            ],
            'dingTalkEventHandlers' => [
            ],

            'sessionIdSalt' => '',
        ];
    }

    public static function relations(): array
    {
        return [];
    }


    public function getSessionId() : string
    {
        return $this->sessionId
            ?? $this->sessionId = sha1($this->url. "::" . $this->sessionIdSalt);
    }

}