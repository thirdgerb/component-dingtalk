<?php


/**
 * Class AppConfig
 * @package Commune\DingTalk\EasyDingTalk\Options
 */

namespace Commune\DingTalk\EasyDingTalk\Options;


use Commune\Support\Option\AbsOption;

/**
 * Easy Ding Talk 的基本配置
 *
 * @property string $corp_id
 * @property string $app_key
 * @property string $app_secret
 * @property string $token
 * @property string $aes_key
 * @property string $sso_secret
 * @property OAuthOption[] $oauth
 */
class AppConfig extends AbsOption
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

            /*
            |-----------------------------------------------------------
            | 【选填】第三方网站 OAuth 授权
            |-----------------------------------------------------------
            | 如果你用到扫码登录、钉钉内免登和密码登录第三方网站，需要配置该项
            */
            'oauth' => [
            ]

        ];
    }

    public static function relations(): array
    {
        return [
            'oauth[]' => OAuthOption::class,
        ];
    }


}