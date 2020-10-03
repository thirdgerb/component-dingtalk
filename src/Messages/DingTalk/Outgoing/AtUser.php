<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Messages\DingTalk\Outgoing;



use Commune\Support\Struct\AStruct;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property-read string $dingtalkId 用户的 id
 * @property-read string $staffId 用户在企业内的 id
 */
class AtUser extends AStruct
{
    public static function stub(): array
    {
        return [
            'dingtalkId' => '',
            'staffId' => ''
        ];
    }

    public static function relations(): array
    {
        return [];
    }


}