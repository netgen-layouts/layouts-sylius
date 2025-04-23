<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\ContentBrowser\Item\Component;

use Netgen\Layouts\Sylius\API\ComponentInterface as APIComponentInterface;

use function array_pop;
use function explode;
use function implode;
use function sprintf;

final class ItemValue
{
    private function __construct(
        private readonly string $componentTypeIdentifier,
        private readonly int $id,
    ) {
    }

    public static function fromValue(string $value): self
    {
        $parts = explode('_', $value);
        $id = (int) array_pop($parts);
        $componentTypeIdentifier = implode('_', $parts);

        return new self($componentTypeIdentifier, $id);
    }

    public static function fromComponent(APIComponentInterface $component): self
    {
        return new self($component->getIdentifier(), $component->getId());
    }

    public function getComponentTypeIdentifier(): string
    {
        return $this->componentTypeIdentifier;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return sprintf('%s_%s', $this->getComponentTypeIdentifier(), $this->getId());
    }
}
