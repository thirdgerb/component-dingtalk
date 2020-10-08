<?php
/*
 * This file is part of the commune/compnt-dingtalk
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk;


use Commune\Blueprint\Configs\PlatformConfig;
use Commune\Chatbot\Hyperf\Platforms\Http\HfHttpConfig;
use Commune\Chatbot\Hyperf\Platforms\Http\HfHttpPlatform;
use Commune\DingTalk\Providers\CompanyBotsServiceProvider;
use Commune\Platform\IPlatformConfig;

/**
 * Commune/studio-hyperf 为钉钉提供的企业 Outgoing 机器人服务端.
 *
 * 提供的基本功能有:
 *
 * - outgoing 机器人: 通过多轮对话控制钉钉, 暂时只提供有限的对话控制能力.
 * - webhook 机器人: 接受钉钉的事件回调, 可主动触发多轮对话.
 * - 钉钉群 webhook : 可以主动推送消息给钉钉群.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class DingTalkPlatformConfig extends IPlatformConfig implements PlatformConfig
{

    public static function stub(): array
    {
        return [
            'id' => 'ding',
            'name' => 'DingTalk',
            'desc' => '钉钉机器人的对接平台',
            'concrete' => HfHttpPlatform::class,
            'bootShell' => null,
            'bootGhost' => false,
            'providers' => [],
            'options' => [
                // 服务器配置
                HfHttpConfig::class => [
                    'server' => [
                        'name' => 'dingtalk',
                        'host' => '127.0.0.1',
                        'port' => 9510,
                    ],
                    'processes' => [],
                    'routes' => [],
                ],
            ],
        ];
    }


}