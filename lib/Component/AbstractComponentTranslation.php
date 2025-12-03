<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Component;

use Sylius\Resource\Model\AbstractTranslation;

abstract class AbstractComponentTranslation extends AbstractTranslation implements ComponentTranslationInterface
{
    final protected int $id;

    final protected string $name;

    final public function getId(): int
    {
        return $this->id;
    }

    final public function getName(): string
    {
        return $this->name;
    }

    final public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
