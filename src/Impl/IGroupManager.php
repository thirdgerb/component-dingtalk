<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Impl;


use Commune\DingTalk\Configs\GroupBotConfig;
use Commune\DingTalk\Contracts\GroupManager;

class IGroupManager implements GroupManager
{

    /**
     * @var GroupBotConfig[]
     */
    protected $urlToGroups = [];

    /**
     * @var GroupBotConfig[]
     */
    protected $sessionIdToGroups = [];

    public function register(GroupBotConfig $group): void
    {
        $url = trim($group->url, '/');
        $this->urlToGroups[$url] = $group;
        $this->sessionIdToGroups[$group->getSessionId()] = $group;
    }

    public function findGroupByUri(string $url): ? GroupBotConfig
    {
        $url = trim($url, '/');
        return $this->urlToGroups[$url] ?? null;
    }

    public function findGroupBySessionId(string $sessionId): ? GroupBotConfig
    {
        return $this->sessionIdToGroups[$sessionId] ?? null;
    }


}