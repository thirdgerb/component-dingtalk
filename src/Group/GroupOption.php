<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Group;


use Commune\Blueprint\Framework\Auth\Supervise;
use Commune\Support\Option\AbsOption;

/**
 * 钉钉群的配置.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property string $name 群的ID
 * @property string $title 群的称呼
 * @property string $uri 提交给钉钉的 outgoing url
 * @property string $scene 场景
 * @property string $entry 群默认的 Context 入口
 *
 * @property int $userLevel 群内用户的基础级别
 *
 * @property string[] $communeEventHandlers 群监听的 Commune 事件, 接受到这些事件后, 用 handler 进行处理.
 *
 * @property string[] $dingTalkEventHandlers 群监听的钉钉事件, 接受到这些事件后, 用 handler 进行处理.
 *
 *
 * @property string|null $dingTalkWebHook 群的回调机器人web hook, 用于异步推送消息给群
 */
class GroupOption extends AbsOption
{
    const IDENTITY = 'name';

    public static function stub(): array
    {
        return [
            'name' => '',
            'title' => '',
            'uri' => '',
            'scene' => '',
            'entry' => '',

            'userLevel' => Supervise::USER,

            'communeEventHandlers' => [
            ],
            'dingTalkEventHandlers' => [

            ],
            'dingTalkWebHook' => null,
        ];
    }

    public static function relations(): array
    {
        return [];
    }


}