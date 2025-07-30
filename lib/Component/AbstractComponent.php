<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Component;

use DateTimeImmutable;
use Netgen\Layouts\Exception\RuntimeException;
use Sylius\Resource\Model\TimestampableTrait;
use Sylius\Resource\Model\ToggleableTrait;
use Sylius\Resource\Model\TranslatableTrait;

use function sprintf;

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

        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        $translation = $this->getTranslation();

        if ($translation instanceof ComponentTranslationInterface) {
            return $translation->getName();
        }

        throw new RuntimeException(
            sprintf('Invalid translation for component of type "%s" with ID "%d"', static::class, $this->id),
        );
    }
}
