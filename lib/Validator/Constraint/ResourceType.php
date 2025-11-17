<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator\Constraint;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

final class ResourceType extends Constraint
{
    #[HasNamedArguments]
    public function __construct(
        public string $message = 'netgen_layouts.sylius.resource_type.type_not_found',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return 'nglayouts_sylius_resource_type';
    }
}
