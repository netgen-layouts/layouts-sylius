<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Component;

use InvalidArgumentException;
use Stringable;

use function count;
use function explode;
use function sprintf;

final class ComponentId implements Stringable
{
    public function __construct(
        private string $componentType,
        private int $id,
    ) {}

    public function __toString(): string
    {
        return sprintf('%s-%s', $this->getComponentType(), $this->getId());
    }

    public static function fromString(string $value): self
    {
        $parts = explode('-', $value);

        if (count($parts) !== 2) {
            throw new InvalidArgumentException(sprintf('Invalid component ID "%s"', $value));
        }

        return new self($parts[0], (int) $parts[1]);
    }

    public static function fromComponent(ComponentInterface $component): self
    {
        return new self($component::getIdentifier(), $component->getId());
    }

    public function getComponentType(): string
    {
        return $this->componentType;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
