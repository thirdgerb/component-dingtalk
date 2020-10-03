<?php
/*
 * This file is part of the commune/compnt-dingtalk
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk;


use Commune\Blueprint\Configs\PlatformConfig;

/**
 * Commune/studio-hyperf 为钉钉提供的企业 Outgoing 机器人服务端.
 *
 * 提供的基本功能有:
 *
 * - 多轮对话控制钉钉, 暂时只提供有限的对话控制能力.
 * - 接受钉钉的事件回调, 可主动触发多轮对话.
 * - 接受 Host 的全局事件, 主动推送消息或触发多轮对话.
 *
 * 注册到 studio 的 config.platforms 中.
 *
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class DingTalkPlatformConfig implements PlatformConfig
{

}