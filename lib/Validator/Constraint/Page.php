<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

final class Page extends Constraint
{
    public string $message = 'netgen_layouts.sylius.page.page_not_found';

    public function validatedBy(): string
    {
        return 'nglayouts_sylius_page';
    }
}
