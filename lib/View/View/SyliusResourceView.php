<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\View\View;

use Netgen\Layouts\View\View;
use Sylius\Resource\Model\ResourceInterface;

final class SyliusResourceView extends View implements SyliusResourceViewInterface
{
    public string $identifier {
        get => 'sylius_resource';
    }

    public function __construct(
        public private(set) ResourceInterface $resource,
        public private(set) string $viewType,
    ) {
        $this
            ->addInternalParameter('resource', $this->resource)
            ->addInternalParameter('view_type', $this->viewType);
    }
}
