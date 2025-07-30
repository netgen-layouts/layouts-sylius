<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\ValueObjectProvider;

use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;

final class ComponentProvider implements ValueObjectProviderInterface
{
    public function __construct(
        private ComponentRepositoryInterface $componentRepository,
    ) {}

    public function getValueObject(mixed $value): ?ComponentInterface
    {
        if ($value === null) {
            return null;
        }

        return $this->componentRepository->load(ComponentId::fromString($value));
    }
}
