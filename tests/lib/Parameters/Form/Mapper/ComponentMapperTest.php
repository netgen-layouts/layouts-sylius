<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Parameters\Form\Mapper\ComponentMapper;
use Netgen\Layouts\Sylius\Parameters\ParameterType\ComponentType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ComponentMapper::class)]
final class ComponentMapperTest extends TestCase
{
    private ComponentMapper $mapper;

    private MockObject&ValueObjectProviderInterface $valueObjectProvider;

    protected function setUp(): void
    {
        $this->valueObjectProvider = $this->createMock(ValueObjectProviderInterface::class);

        $this->mapper = new ComponentMapper();
    }

    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserType::class, $this->mapper->getFormType());
    }

    public function testMapOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_component',
                'block_prefix' => 'ngcb_sylius_component',
            ],
            $this->mapper->mapOptions(
                ParameterDefinition::fromArray([
                    'type' => new ComponentType($this->valueObjectProvider),
                ]),
            ),
        );
    }
}
