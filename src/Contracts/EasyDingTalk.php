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


/**
 * @property \EasyDingTalk\Auth\SsoClient $sso
 * SSO 相关功能
 *
 * @property \EasyDingTalk\Auth\OAuthClient $oauth
 * OAuth 相关功能
 *
 * @property \EasyDingTalk\Chat\Client $chat
 * 群会话管理, 可以发送群消息.
 *
 * @property \EasyDingTalk\Role\Client $role
 * 管理角色
 *
 * @property \EasyDingTalk\User\Client $user
 * 用户相关的信息
 *
 * @property \EasyDingTalk\Media\Client $media
 * 媒体文件上传.
 *
 * @property \EasyDingTalk\H5app\Client $h5app
 * H5相关功能
 *
 * @property \EasyDingTalk\Health\Client $health
 * 运动数据
 *
 * @property \EasyDingTalk\Report\Client $report
 * 用户日志相关数据.
 *
 * @property \EasyDingTalk\Checkin\Client $checkin
 * 签到记录
 *
 * @property \EasyDingTalk\Contact\Client $contact
 * 企业外部联系人
 *
 * @property \EasyDingTalk\Process\Client $process
 * 发起审批实例
 *
 * @property \EasyDingTalk\Calendar\Client $calendar
 * 创建日程
 *
 * @property \EasyDingTalk\Callback\Client $callback
 * 回调接口管理.
 *
 * @property \EasyDingTalk\Microapp\Client $microapp
 * 小程序功能
 *
 * @property \EasyDingTalk\Schedule\Client $schedule
 * 用户待办功能.
 *
 * @property \EasyDingTalk\Blackboard\Client $blackboard
 * 用户公告
 *
 * @property \EasyDingTalk\Attendance\Client $attendance
 * 考勤模块
 *
 * @property \EasyDingTalk\Department\Client $department
 * 部门管理模块
 *
 * @property \EasyDingTalk\Conversation\Client $conversation
 * 主动给用户推送工作通知.
 *
 * @property \EasyDingTalk\Kernel\Http\Client $client
 * http请求客户端
 *
 * @property \Monolog\Logger $logger
 * 日志模块
 *
 * @property \EasyDingTalk\Kernel\Encryption\Encryptor $encryptor
 * 加密响应工具
 *
 * @property \EasyDingTalk\Kernel\AccessToken $access_token
 * 记录/获取 AccessToken
 */
interface EasyDingTalk
{

}