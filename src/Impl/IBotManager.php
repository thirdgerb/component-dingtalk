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


use Commune\DingTalk\Configs\BotConfig;
use Commune\DingTalk\Contracts\BotManager;

class IBotManager implements BotManager
{

    /**
     * @var BotConfig[]
     */
    protected $urlToGroups = [];

    /**
     * @var BotConfig[]
     */
    protected $sessionIdToGroups = [];

    public function register(BotConfig $group): void
    {
        $url = trim($group->url, '/');
        $this->urlToGroups[$url] = $group;
        $this->sessionIdToGroups[$group->getSessionId()] = $group;
    }

    public function findBotByUri(string $url): ? BotConfig
    {
        $url = trim($url, '/');
        return $this->urlToGroups[$url] ?? null;
    }

    public function findBotBySessionId(string $sessionId): ? BotConfig
    {
        return $this->sessionIdToGroups[$sessionId] ?? null;
    }


}