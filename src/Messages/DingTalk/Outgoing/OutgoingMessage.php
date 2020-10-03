<?php

/*
 * This file is part of the commune/compnt-dingding
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Messages\DingTalk\Outgoing;


use Commune\DingTalk\Group\GroupOption;
use Commune\DingTalk\Messages\DingTalk\Incoming\DTMessage;
use Commune\Message\Host\Convo\IText;
use Commune\Message\Intercom\IInputMsg;
use Commune\Protocals\HostMsg;
use Commune\Protocals\Intercom\InputMsg;
use Commune\Support\Message\AbsMessage;

/**
 * 来自 DingTalk 的 outgoing message, 从中解析出 commune 的 message
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property-read string $msgtype 消息类型
 * @property-read string $msgId 消息ID
 * @property-read int $createAt 时间戳, 毫秒
 * @property-read string $conversationType 对话类型, 1 表示个人, 2 表示群聊
 * @property-read string $conversationTitle 群聊的会话标题
 * @property-read string $conversationId 会话的 id
 * @property-read string $senderId  发送者的 id
 * @property-read string $senderNick 发送者的昵称
 * @property-read string $senderCorpId 发送者的企业群 id. 内部机器人才有.
 * @property-read string $senderStaffId 发送者在企业内的 id
 * @property-read string $chatbotUserId 机器人的 id
 * @property-read AtUser[] $atUsers 消息 at 用户的 id
 */
class OutgoingMessage extends AbsMessage
{

    public static function stub(): array
    {
        return [
            "msgtype"=> "",
            "msgId"=> "",
            "createAt"=> 0,
            "conversationType"=> "",
            "conversationId"=> "",
            "conversationTitle"=> "",
            "senderId"=> "",
            "senderNick"=> "",
            "senderCorpId"=> "",
            "senderStaffId"=> "",
            "chatbotUserId"=>"",
            "atUsers"=> [
            ],
        ];
    }

    /**
     * @return bool
     */
    public function isSingleConversation() : bool
    {
        return $this->conversationType == '1';
    }

    /**
     * @return bool
     */
    public function isGroupConversation() : bool
    {
        return $this->conversationType == '2';
    }

    /**
     * @return HostMsg
     */
    public function getHostMessage() : HostMsg
    {
        switch ($this->msgtype) {
            case DTMessage::TYPE_TEXT :
                $data = $this->_data['text'] ?? [];
                return IText::instance($data['content'] ?? '');
            default:
                return IText::instance('');
        }
    }


    /**
     * @param GroupOption $group
     * @return InputMsg
     */
    public function toInputMessage(GroupOption $group) : InputMsg
    {
        return IInputMsg::instance(
            $this->getHostMessage(),
            $this->makeSessionId($group->name),
            $this->senderId,
            $this->senderNick,
            '',
            0,
            $this->msgId,
            $group->scene
        );
    }

    public function makeSessionId(string $groupId) : string
    {
        return sha1("conversation:{$this->conversationId}:group:$groupId");
    }

    public static function relations(): array
    {
        return [
            'atUsers[]' => AtUser::class,
        ];
    }

    public function isEmpty(): bool
    {
        return false;
    }


}