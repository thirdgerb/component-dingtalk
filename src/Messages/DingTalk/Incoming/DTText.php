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


use Commune\Protocals\HostMsg;
use Commune\Protocals\HostMsg\Convo\VerbalMsg;
use Commune\Support\Message\AbsMessage;

/**
 * Ding talk 的 text 消息类型
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property string $text
 * @property string[] $atMobiles
 * @property bool $isAtAll
 */
class DTText extends AbsMessage implements VerbalMsg, AtSomeone, DTMessage
{

    public static function instance(string $text) : self
    {
        return new static(['text' => $text]);
    }

    public static function stub(): array
    {
        return [
            'text' => '',
            'atMobiles' => [],
            'isAtAll' => false,
        ];
    }

    public static function relations(): array
    {
        return [];
    }

    public function getProtocalId(): string
    {
        return $this->text;
    }

    public function getLevel(): string
    {
        return HostMsg::INFO;
    }

    public function isEmpty(): bool
    {
        $text = $this->text;
        return empty($text);
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function at(string $mobilePhone): AtSomeone
    {
        $atMobiles = $this->_data['atMobiles'] ?? [];
        $atMobiles[] = $mobilePhone;
        $this->_data['atMobiles'] = array_unique($atMobiles);
        return $this;
    }

    public function atAll(): AtSomeone
    {
        $this->isAtAll = true;
        return $this;
    }

    public function toDingTalkData(): array
    {
        $content = $this->text;

        foreach ($this->atMobiles as $mobile) {
            $content .= " @$mobile";
        }

        return [
            'msgtype' => DTMessage::TYPE_TEXT,
            'text' => [
                'content' => $content,
            ],
            'at' => [
                'atMobiles' => $this->atMobiles,
                'isAtAll' => $this->isAtAll
            ],
        ];
    }


}