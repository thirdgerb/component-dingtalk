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
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property string $title
 * @property string $text
 * @property bool $btnOrientation
 * @property DTButton[] $buttons
 */
class DTActionCard extends AbsMessage implements DTMessage, VerbalMsg, WithButton
{

    public static function instance(
        string $title,
        string $text,
        bool $btnOrientation = false
    ) : self
    {
        return new static(get_defined_vars());
    }


    public static function stub(): array
    {
        return [
            'title' => '',
            'text' => '',
            'btnOrientation' => false,
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
        $data =  [
            "actionCard" => [
                "title" => $this->title,
                "text" => $this->text,
                "btnOrientation" => $this->btnOrientation ? '1' : '0',
            ],
            "msgtype" => DTMessage::TYPE_ACTION_CARD,
        ];

        $buttons = $this->buttons;
        $count = count($buttons);
        if ($count === 1) {
            $button = current($buttons);
            $data['actionCard']['singleTitle'] = $button->title;
            $data['actionCard']['singleURL'] = $button->url;
        } elseif ($count > 1) {
            $data['actionCard']['btns'] = [];
            foreach ($buttons as $button) {
                $data['actionCard']['btns'][] = [
                    'title' => $button->title,
                    'actionURL' => $button->url
                ];
            }
        }

        return $data;
    }

    public function getProtocalId(): string
    {
        return $this->title;
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
        $title = $this->title;
        if (!empty($title)) {
            $output .= "# $title\n\n";
        }

        $text = $this->text;
        $output .= $text;

        foreach ($this->buttons as $button) {
            $btnTitle = $button->title;
            $btnUrl = $button->url;
            $output .= "\n- [$btnTitle]($btnUrl)";
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