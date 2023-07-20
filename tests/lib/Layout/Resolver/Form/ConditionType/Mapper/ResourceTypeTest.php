<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\ResourceType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[CoversClass(ResourceType::class)]
final class ResourceTypeTest extends TestCase
{
    private ResourceType $mapper;

    protected function setUp(): void
    {
        $allowedResources = [
            'Sylius\Component\Product\Model\ProductInterface' => 'product',
            'Sylius\Component\Core\Model\Product' => 'product-test',
            'Sylius\Component\Taxonomy\Model\TaxonInterface' => 'taxon',
            'Sylius\Component\Taxonomy\Model\Taxon' => 'taxon_test',
        ];

        $this->mapper = new ResourceType($allowedResources);
    }

    public function testGetFormType(): void
    {
        self::assertSame(ChoiceType::class, $this->mapper->getFormType());
    }

    public function testGetFormOptions(): void
    {
        $typesList = [
            'Product' => 'product',
            'Product test' => 'product-test',
            'Taxon' => 'taxon',
            'Taxon test' => 'taxon_test',
        ];

        self::assertSame(
            [
                'choices' => $typesList,
                'choice_translation_domain' => false,
                'multiple' => true,
                'expanded' => true,
            ],
            $this->mapper->getFormOptions(),
        );
    }
}
