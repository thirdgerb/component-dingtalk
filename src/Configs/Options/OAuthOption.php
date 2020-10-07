<?php

namespace Commune\DingTalk\Configs\Options;


use Commune\Support\Option\AbsOption;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * OAuth 的配置.
 *
 *
 * @property string $client_id
 * @property string $client_secret
 * @property string $scope
 * @property string $redirect
 */
class OAuthOption extends AbsOption
{
    public static function stub(): array
    {
        return [
            /*
             |-------------------------------------------
             | `app-01` 为你自定义的名称，不要重复即可
             |-------------------------------------------
             | 数组内需要配置 `client_id`, `client_secret`, `scope` 和 `redirect` 四项
             |
             | `client_id` 为钉钉登录应用的 `appId`
             | `client_secret` 为钉钉登录应用的 `appSecret`
             | `scope`:
             |     - 扫码登录第三方网站和密码登录第三方网站填写 `snsapi_login`
             |     - 钉钉内免登第三方网站填写 `snsapi_auth`
             | `redirect` 为回调地址
             */
            'client_id' => '', //'dingoaxmia0afj234f7',
            'client_secret' => '', //'c4x4el0M6JqMC3VQP80-cFasdf98902jklFSUVdAOIfasdo98a2',
            'scope' => '', //'snsapi_login',
            'redirect' => '', //'https://easydingtalk.org/callback',
        ];
    }

    public static function relations(): array
    {
        return [];
    }


}