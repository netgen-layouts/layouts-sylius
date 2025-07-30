<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

final class Component extends Constraint
{
    public string $message = 'netgen_layouts.sylius.component.component_not_found';

    /**
     * If set to true, the constraint will accept values for non existing components.
     */
    public bool $allowInvalid = false;

    public function validatedBy(): string
    {
        return 'nglayouts_sylius_component';
    }
}
