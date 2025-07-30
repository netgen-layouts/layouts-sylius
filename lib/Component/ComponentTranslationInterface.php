<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Component;

use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\TranslationInterface;

interface ComponentTranslationInterface extends ResourceInterface, TranslationInterface
{
    public function getName(): string;

    public function setName(string $name): void;
}
