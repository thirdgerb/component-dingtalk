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
 *
 * @property string $title 标题
 * @property string $text 描述
 * @property string $picUrl 图片
 * @property string $messageUrl 连接的地址
 */
class DTLink extends AbsMessage implements VerbalMsg, DTMessage
{

    public static function instance(
        string $text,
        string $messageUrl,
        string $title = '',
        string $picUrl = ''
    ) : self
    {
        return new static([
            "text" => $text,
            "title"=> $title,
            "picUrl"=> $picUrl,
            "messageUrl" => $messageUrl,
        ]);
    }

    public static function stub(): array
    {
        return [
            "text" =>  "",
            "title"=> "",
            "picUrl"=> "",
            "messageUrl" => '',
        ];
    }

    public static function relations(): array
    {
        return [];
    }

    public function toDingTalkData(): array
    {
        return [
            'msgtype' => DTMessage::TYPE_LINK,
            'link' => [
                'text' => $this->text,
                'messageUrl' => $this->messageUrl,
                'title' => $this->title,
                'picUrl' => $this->picUrl,
            ],
        ];
    }

    public function getProtocalId(): string
    {
        return $this->messageUrl;
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
        $text = $this->text;
        $messageUrl = $this->messageUrl;
        $title = $this->title;
        $picUrl = $this->picUrl;

        $output = '';
        if (!empty($title)) {
            $output .= "# $title\n\n";
        }

        if (!empty($picUrl)) {
            $output .= "![$text]($picUrl $title)";
        }
        $output .= "[$text]($messageUrl)";

        return $output;
    }


}