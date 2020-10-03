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


use Commune\Blueprint\Platform\Adapter;
use Commune\Blueprint\Platform\Packer;

class DingTalkPacker implements Packer
{
    public function isInvalidInput(): ? string
    {
        // TODO: Implement isInvalidInput() method.
    }

    public function adapt(string $adapterName, string $appId): Adapter
    {
        // TODO: Implement adapt() method.
    }

    public function fail(string $error): void
    {
        // TODO: Implement fail() method.
    }

    public function destroy(): void
    {
        // TODO: Implement destroy() method.
    }


}