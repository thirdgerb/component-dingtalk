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


/**
 * 将消息转化为 ding talk 的消息.
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface DTMessage
{
    const TYPE_TEXT = 'text';
    const TYPE_MARKDOWN = 'markdown';
    const TYPE_LINK = 'link';
    const TYPE_ACTION_CARD = 'actionCard';
    const TYPE_FEED_CARD = 'feedCard';


    public function toDingTalkData() : array;

}