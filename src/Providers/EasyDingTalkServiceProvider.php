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


use Commune\Container\ContainerContract;
use Commune\Contracts\ServiceProvider;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property string $corp_id
 * @property string $app_key
 * @property string $app_secret
 * @property string $token
 * @property string $aes_key
 * @property string $sso_secret
 */
class EasyDingTalkServiceProvider extends ServiceProvider
{

    public static function stub(): array
    {
        return [
            /*
           |-----------------------------------------------------------
           | 【必填】企业 corpId
           |-----------------------------------------------------------
           */
            'corp_id' => '',

            /*
            |-----------------------------------------------------------
            | 【必填】应用 AppKey
            |-----------------------------------------------------------
            */
            'app_key' => '',

            /*
            |-----------------------------------------------------------
            | 【必填】应用 AppSecret
            |-----------------------------------------------------------
            */
            'app_secret' => '',

            /*
            |-----------------------------------------------------------
            | 【选填】加解密
            |-----------------------------------------------------------
            | 此处的 `token` 和 `aes_key` 用于事件通知的加解密
            | 如果你用到事件回调功能，需要配置该两项
            */
            'token' => '',
            'aes_key' => '',

            /*
            |-----------------------------------------------------------
            | 【选填】后台免登配置信息
            |-----------------------------------------------------------
            | 如果你用到应用管理后台免登功能，需要配置该项
            */
            'sso_secret' => '',

        ];
    }

    public function getDefaultScope(): string
    {
        // TODO: Implement getDefaultScope() method.
    }


    public function boot(ContainerContract $app): void
    {
        // TODO: Implement boot() method.
    }

    public function register(ContainerContract $app): void
    {
        // TODO: Implement register() method.
    }


}