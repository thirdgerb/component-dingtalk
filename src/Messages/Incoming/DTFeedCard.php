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


use Commune\Protocals\HostMsg;
use Commune\Protocals\HostMsg\Convo\VerbalMsg;
use Commune\Support\Message\AbsMessage;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property DTButton[] $buttons
 */
class DTFeedCard extends AbsMessage implements VerbalMsg, DTMessage, WithButton
{

    public static function instance() : self
    {
        return new static([]);
    }

    public static function stub(): array
    {
        return [
            'buttons' => [],
        ];
    }

    public static function relations(): array
    {
        return [
            'buttons[]' => DTButton::class,
        ];
    }

    public function toDingTalkData(): array
    {
        $links = [];
        foreach ($this->buttons as $button) {

            $links[] = [
                "title" => $button->title,
                "messageURL" => $button->url,
                "picURL" => $button->pic,
            ];
        }
        $data =  [
            "feedCard" => [
                "links" => $links
            ],
            "msgtype" => DTMessage::TYPE_FEED_CARD,
        ];

        return $data;
    }

    public function getProtocalId(): string
    {
        return static::class;
    }

    public function getLevel(): string
    {
        return HostMsg::INFO;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function getText(): string
    {
        $output = '';
        foreach ($this->buttons as $button) {
            $btnTitle = $button->title;
            $btnPic = $button->pic;
            $btnUrl = $button->url;
            $output .= "\n- [$btnTitle]($btnUrl) ![$btnTitle]($btnPic)";
        }

        return $output;
    }

    public function withBtn(string $title, string $url, string $pic = null): WithButton
    {
        $buttons = $this->buttons;
        $buttons[] = new DTButton([
            'title' => $title,
            'url' => $url,
            'pic' => $pic,
        ]);
        $this->buttons = $buttons;
        return $this;
    }




}