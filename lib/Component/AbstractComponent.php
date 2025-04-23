<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Component;

use Netgen\Layouts\Sylius\API\ComponentInterface;
use Netgen\Layouts\Sylius\API\ComponentTranslationInterface;
use Sylius\Resource\Model\TimestampableTrait;
use Sylius\Resource\Model\ToggleableTrait;
use Sylius\Resource\Model\TranslatableTrait;
use Sylius\Resource\Model\TranslationInterface;

abstract class AbstractComponent implements ComponentInterface
{
    use TimestampableTrait;
    use ToggleableTrait;
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    protected int $id;

    public function __construct()
    {
        $this->initializeTranslationsCollection();

        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        $componentTranslation = $this->getComponentTranslation();

        return $componentTranslation->getName();
    }

    protected function getComponentTranslation(): ComponentTranslationInterface|TranslationInterface
    {
        return $this->getTranslation();
    }
}
