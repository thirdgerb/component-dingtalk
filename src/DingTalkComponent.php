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
use Commune\Framework\Component\AComponentOption;

/**
 * Commune/studio-hyperf 的 ding talk 组件.
 *
 * 将之注册到 studio 的 config.components 中, 获得组件的能力.
 *
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class DingTalkComponent extends AComponentOption
{
    public static function stub(): array
    {
        return [

        ];
    }

    public static function relations(): array
    {
        return [];
    }

    public function bootstrap(App $app): void
    {
    }


}