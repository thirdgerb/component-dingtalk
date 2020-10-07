<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Messages\Incoming;


use Commune\Support\Struct\AStruct;

/**
 * 按钮的数据结构
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property string $title 按钮的标题
 * @property string $url 按钮的 action, 通常是 url
 * @property string|null $pic 按钮的图片展示.
 */
class DTButton extends AStruct
{
    public static function stub(): array
    {
        return [
            'title' => '',
            'url' => '',
            'pic' => null,
        ];
    }

    public static function relations(): array
    {
        return [];
    }


}