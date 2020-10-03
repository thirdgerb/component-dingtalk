<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Messages\DingTalk\Incoming;


/**
 * 消息类型有按钮.
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface WithButton
{
    public function withBtn(string $title, string $url, string $pic = null) : WithButton;

}