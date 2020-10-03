<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Group;


use Commune\Protocals\IntercomMsg;

/**
 * 钉钉群的服务封装
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface GroupServiceInterface
{

    /**
     * 获取群的基本配置
     * @return GroupOption
     */
    public function getOption() : GroupOption;



    /**
     * 将一个 Commune 的消息体主动推送给钉钉群.
     * @param IntercomMsg $message
     * @return bool
     */
    public function sendAsyncMessage(IntercomMsg $message) : bool;

}