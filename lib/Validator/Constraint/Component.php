<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

final class Component extends Constraint
{
    public string $message = 'netgen_layouts.sylius.component.component_not_found';

    public function validatedBy(): string
    {
        return 'nglayouts_sylius_component';
    }
}
