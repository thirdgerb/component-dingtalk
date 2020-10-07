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
use Commune\DingTalk\Configs\GroupBotConfig;
use Commune\DingTalk\Contracts\GroupManager;
use Commune\DingTalk\DingTalk\Controller\OutgoingController;
use Commune\DingTalk\Impl\IGroupManager;
use Hyperf\HttpServer\Router\Router;


/**
 * 企业群机器人服务
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property GroupBotConfig[] $groupBots
 */
class GroupBotsServiceProvider extends ServiceProvider
{
    public static function stub(): array
    {
        return [
            'groupBots' => [
            ],
        ];
    }

    public static function relations(): array
    {
        return [
            'groupBots[]' => GroupBotConfig::class,
        ];
    }

    public function getDefaultScope(): string
    {
        return self::SCOPE_PROC;
    }

    public function boot(ContainerContract $app): void
    {
        /**
         * @var GroupManager $manager
         */
        $manager = $app->make(GroupManager::class);

        $bots = $this->groupBots;
        foreach ($bots as $bot) {
            $manager->register($bot);
        }

        HfRouter::add(function() use ($bots) {
            foreach ($bots as $bot) {
                Router::post($bot->url, [OutgoingController::class, 'onReceive']);
            }
        });

    }

    public function register(ContainerContract $app): void
    {
        $app->singleton(
            GroupManager::class,
            IGroupManager::class
        );
    }


}