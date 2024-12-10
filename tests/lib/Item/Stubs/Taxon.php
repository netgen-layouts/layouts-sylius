<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\Stubs;

use Sylius\Component\Taxonomy\Model\Taxon as BaseTaxon;

final class Taxon extends BaseTaxon
{
    public function __construct(int $id, string $name, ?string $slug = null, bool $enabled = true)
    {
        parent::__construct();

        $this->id = $id;

        $this->currentLocale = 'en';
        $this->setName($name);
        $this->setSlug($slug);
        $this->setEnabled($enabled);
    }
}
