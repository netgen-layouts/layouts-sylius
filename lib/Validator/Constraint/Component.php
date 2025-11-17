<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator\Constraint;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

final class Component extends Constraint
{
    #[HasNamedArguments]
    public function __construct(
        /**
         * If set to true, the constraint will accept values for non existing components.
         */
        public bool $allowInvalid = false,
        public string $message = 'netgen_layouts.sylius.component.component_not_found',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return 'nglayouts_sylius_component';
    }
}
