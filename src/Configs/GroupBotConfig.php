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
 * @property string $privateEntry 机器人的私聊语境入口. 私聊是无法异步推送的.
 * 
 * @property bool $isPublic 机器人是公共的, 还是私人的. 公共的, 则大家共享同一个会话. 私人的, 则每个用户和机器人的对话是独立的. 
 *
 * ## 用户权限
 *
 * @property int[] $userLevelMap  群内用户的权限列表.
 *

 * ## 异步响应逻辑
 *
 * @property string|null $asyncWebHooks 机器人的异步回调接口. 异步消息会逐个推送到这些接口上. 
 * @property string[] $listens 当前机器人监听的事件. 触发事件时, 会向机器人发送异步消息.
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
            // 机器人的 id, 也作为 Scene 参数传给 ghost
            'id' => '',
            // 机器人的名称
            'botName' => '',
            // 钉钉分配的 agentId, 似乎用处不大
            'agentId' => 0,
            // 钉钉分配的 appKey
            'appKey' => '',
            // 钉钉机器人用于签名校验的 appSecret
            'appSecret' => '',

            // 机器人的 web url, 不包含域名. 
            // 例如 communechatbot.com/ding-talk/bots/defaults
            // 对应的 url 是 ding-talk/bots/defaults
            // 用于钉钉的 outgoing 消息推送地址. 
            'url' => '',
            // 机器人的群入口对话.
            'entry' => '',
            // 机器人的私聊入口对话
            'privateEntry' => '',

            // 定义使用机器人的用户所属的级别.
            'userLevelMap' => [
                // 使用  string $id => int $level  的格式
                // 'userId' => CloneGuest::SUPERVISE,
            ],
            
            // 机器人是公共的, 还是 1对1 的.
            // 决定每个用户对话时的 conversationId 是否一致.
            'isPublic' => true,

            // 机器人接受消息时的handler, 通常不用修改
            'outgoingHandler' => IOutgoingHandler::class,

            // 机器人的异步回调接口. 异步消息会推送到这些接口上.
            // 通常是群可以建立消息类型的机器人, 方便异步消息推送. 
            'asyncWebHooks' => [],
            
            // 机器人监听的事件.
            // 当一些回调事件被触发时, 会主动推送消息给监听这些事件的机器人. 
            'listens' => [],
        ];
    }

    public static function relations(): array
    {
        return [];
    }


    public function getSessionId() : string
    {
        return $this->sessionId
            ?? $this->sessionId = sha1($this->url. "::" . $this->id);
    }

}