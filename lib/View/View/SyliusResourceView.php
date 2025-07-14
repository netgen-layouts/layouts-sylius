<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\View\View;

use Netgen\Layouts\View\View;
use Sylius\Resource\Model\ResourceInterface;

final class SyliusResourceView extends View implements SyliusResourceViewInterface
{
    public function __construct(ResourceInterface $resource, string $viewType)
    {
        $this->parameters['resource'] = $resource;
        $this->parameters['view_type'] = $viewType;
    }

    public function getResource(): ResourceInterface
    {
        return $this->parameters['resource'];
    }

    public function getViewType(): string
    {
        return $this->parameters['view_type'];
    }

    public static function getIdentifier(): string
    {
        return 'sylius_resource';
    }
}
