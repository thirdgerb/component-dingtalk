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


use Commune\Support\Message\AbsMessage;

class DTEmpty extends AbsMessage implements DTMessage
{
    public static function stub(): array
    {
        return [];
    }

    public static function relations(): array
    {
        return [];
    }

    public function toDingTalkData(): array
    {
        return ['msgtype' => 'empty'];
    }

    public function isEmpty(): bool
    {
        return true;
    }


}