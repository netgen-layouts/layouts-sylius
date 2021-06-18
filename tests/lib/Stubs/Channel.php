<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Stubs;

use Sylius\Component\Core\Model\Channel as BaseChannel;

final class Channel extends BaseChannel
{
    public function __construct(int $id, string $code)
    {
        parent::__construct();

        $this->id = $id;
        $this->code = $code;
    }
}
