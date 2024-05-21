<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\ResourceType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\Product;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\Taxon;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[CoversClass(ResourceType::class)]
final class ResourceTypeTest extends TestCase
{
    private ResourceType $mapper;

    protected function setUp(): void
    {
        $allowedResources = [
            ProductInterface::class => 'product',
            Product::class => 'product_test',
            TaxonInterface::class => 'taxon',
            Taxon::class => 'taxon_test',
        ];

        $this->mapper = new ResourceType($allowedResources);
    }

    public function testGetFormType(): void
    {
        self::assertSame(ChoiceType::class, $this->mapper->getFormType());
    }

    public function testGetFormOptions(): void
    {
        self::assertSame(
            [
                'product',
                'product-test',
                'taxon',
                'taxon_test',
            ],
            $this->mapper->getFormOptions()['choices'],
        );
    }
}
