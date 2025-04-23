<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\API;

use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\TimestampableInterface;
use Sylius\Resource\Model\ToggleableInterface;
use Sylius\Resource\Model\TranslatableInterface;

interface ComponentInterface extends ResourceInterface, ToggleableInterface, TimestampableInterface, TranslatableInterface
{
    public static function getIdentifier(): string;

    public function getName(): string;
}
