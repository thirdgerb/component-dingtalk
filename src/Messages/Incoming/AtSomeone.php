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


interface AtSomeone
{

    public function at(string $mobilePhone) : AtSomeone;

    public function atAll() : AtSomeone;

}