<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\Form\Mapper\ProductMapper;
use Netgen\Layouts\Sylius\Parameters\ParameterType\ProductType as ParameterType;
use Netgen\Layouts\Sylius\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProductMapper::class)]
final class ProductMapperTest extends TestCase
{
    private ProductMapper $mapper;

    private Stub&ProductRepositoryInterface $repositoryStub;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(ProductRepositoryInterface::class);
        $this->mapper = new ProductMapper();
    }

    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserType::class, $this->mapper->getFormType());
    }

    public function testMapOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_product',
                'required' => false,
            ],
            $this->mapper->mapOptions(ParameterDefinition::fromArray(['type' => new ParameterType($this->repositoryStub), 'isRequired' => false])),
        );
    }
}
