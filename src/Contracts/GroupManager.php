<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Contracts;

use Commune\DingTalk\Configs\GroupBotConfig;

/**
 * 可以在钉钉里对多个群相互控制的模块.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface GroupManager
{
    /**
     * 注册一个 Group
     *
     * @param GroupBotConfig $group
     */
    public function register(GroupBotConfig $group) : void;

    /**
     * 根据 Outgoing 机器人访问的 url, 来找到 Group 配置.
     *
     * @param string $url
     * @return GroupBotConfig|null
     */
    public function findGroupByUri(string $url) : ? GroupBotConfig;


    /**
     * 根据 sessionId 获取 Group 配置. 通常用于异步发送消息的场景.
     *
     * @param string $sessionId
     * @return GroupBotConfig|null
     */
    public function findGroupBySessionId(string $sessionId) : ? GroupBotConfig;
}