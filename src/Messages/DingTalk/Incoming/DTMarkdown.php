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
 * ding talk 的 markdown 类型消息
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property string $title
 * @property string $text
 * @property string[] $atMobiles
 * @property bool $isAtAll
 *
 */
class DTMarkdown extends AbsMessage implements VerbalMsg, AtSomeone, DTMessage
{

    public static function instance(string $title, string $text) : self
    {
        return new static([
            'title' => $title,
            'text' => $text
        ]);
    }


    public static function stub(): array
    {
        return [
            'title' => '',
            'text' => '',
            'atMobiles' => [],
            'isAtAll' => false,
        ];
    }

    public static function relations(): array
    {
        return [];
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
        return [
            'msgtype' => DTMessage::TYPE_MARKDOWN,
            'markdown' => [
                'title' => $this->title,
                'text' => $this->text,
            ],
            'at' => [
                'atMobiles' => $this->atMobiles,
                'isAtAll' => $this->isAtAll,
            ]
        ];
    }

    public function getProtocalId(): string
    {
        return $this->getText();
    }

    public function getLevel(): string
    {
        return HostMsg::INFO;
    }

    public function isEmpty(): bool
    {
        $text = $this->getText();
        return empty($text);
    }

    public function getText(): string
    {
        $title = "# " . $this->title;
        $text = $this->text;
        return "$title\n\n$text";
    }


}