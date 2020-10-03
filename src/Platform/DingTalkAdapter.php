<?php
/*
 * This file is part of the commune/compnt-dingtalk
 *
 * (c) thirdgerb <thirdgerb@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Commune\DingTalk\Platform;


use Commune\Blueprint\Kernel\Protocals\AppRequest;
use Commune\Blueprint\Kernel\Protocals\AppResponse;
use Commune\Blueprint\Platform\Adapter;

class DingTalkAdapter implements Adapter
{
    public function isInvalidRequest(): ? string
    {
        // TODO: Implement isInvalidRequest() method.
    }

    public function getRequest(): AppRequest
    {
        // TODO: Implement getRequest() method.
    }

    public function sendResponse(AppResponse $response): void
    {
        // TODO: Implement sendResponse() method.
    }

    public function destroy(): void
    {
        // TODO: Implement destroy() method.
    }


}