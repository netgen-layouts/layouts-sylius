<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\API;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface ComponentInterface extends ResourceInterface, ToggleableInterface, TimestampableInterface, TranslatableInterface
{
    public static function getIdentifier(): string;

    public function getName(): string;
}
