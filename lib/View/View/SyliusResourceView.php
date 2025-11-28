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

    public ResourceInterface $resource {
        get => $this->getParameter('resource');
    }

    public string $viewType {
        get => $this->getParameter('view_type');
    }

    public function __construct(ResourceInterface $resource, string $viewType)
    {
        $this
            ->addInternalParameter('resource', $resource)
            ->addInternalParameter('view_type', $viewType);
    }
}
