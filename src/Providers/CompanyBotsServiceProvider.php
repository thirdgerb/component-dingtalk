<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Providers;

use Commune\Chatbot\Hyperf\Platforms\Http\HfRouter;
use Commune\Container\ContainerContract;
use Commune\Contracts\ServiceProvider;
use Commune\DingTalk\Configs\BotConfig;
use Commune\DingTalk\Contracts\BotManager;
use Commune\DingTalk\DingTalk\Controller\OutgoingController;
use Commune\DingTalk\Impl\IBotManager;
use Hyperf\HttpServer\Router\Router;


/**
 * 企业机器人服务
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property BotConfig[] $bots
 */
class CompanyBotsServiceProvider extends ServiceProvider
{
    public static function stub(): array
    {
        return [
            'bots' => [
            ],
        ];
    }

    public static function relations(): array
    {
        return [
            'bots[]' => BotConfig::class,
        ];
    }

    public function getDefaultScope(): string
    {
        return self::SCOPE_PROC;
    }

    public function boot(ContainerContract $app): void
    {
        /**
         * @var BotManager $manager
         */
        $manager = $app->make(BotManager::class);

        $bots = $this->bots;
        foreach ($bots as $bot) {
            $manager->register($bot);
        }

        // 注册路由. 默认机器人在 Http 中启动.
        HfRouter::add(function() use ($bots) {
            foreach ($bots as $bot) {
                Router::post($bot->url, [OutgoingController::class, 'onReceive']);
            }
        });

    }

    public function register(ContainerContract $app): void
    {
        $app->singleton(
            BotManager::class,
            IBotManager::class
        );
    }


}