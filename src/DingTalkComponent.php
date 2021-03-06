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


use Commune\Blueprint\Framework\App;
use Commune\DingTalk\Configs\EasyDingTalkConfig;
use Commune\DingTalk\Configs\BotConfig;
use Commune\DingTalk\Providers\CompanyBotsServiceProvider;
use Commune\Framework\Component\AComponentOption;

/**
 * Commune/studio-hyperf 的 ding talk 组件.
 *
 * 将之注册到 studio 的 config.components 中, 获得组件的能力.
 *
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read BotConfig[] $bots    机器人的相关配置
 * @property-read EasyDingTalkConfig $easyDingTalk  easy ding talk 的相关配置.
 */
class DingTalkComponent extends AComponentOption
{
    public static function stub(): array
    {
        return [
            'bots' => [
            ],
            'easyDingTalk' => [
            ],
        ];
    }

    public static function relations(): array
    {
        return [
            'easyDingTalk' => EasyDingTalkConfig::class,
            'bots[]' => BotConfig::class,
        ];
    }

    public function bootstrap(App $app): void
    {
        // 注册机器人的配置与服务.
        $registry = $app->getServiceRegistry();
        $registry->registerProcProvider(
            new CompanyBotsServiceProvider([
                'bots' => $this->bots
            ]),
            false
        );
    }


}