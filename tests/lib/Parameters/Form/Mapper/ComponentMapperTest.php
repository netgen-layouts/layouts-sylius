<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\Form\Mapper\ComponentMapper;
use Netgen\Layouts\Sylius\Parameters\ParameterType\ComponentType as ParameterType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ComponentMapper::class)]
final class ComponentMapperTest extends TestCase
{
    private ComponentMapper $mapper;

    protected function setUp(): void
    {
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
                'required' => false,
                'custom_params' => [
                    'component_type_identifier' => 'banner_component',
                ],
            ],
            $this->mapper->mapOptions(
                ParameterDefinition::fromArray([
                    'type' => new ParameterType(),
                    'isRequired' => false,
                    'options' => [
                        'component_type_identifier' => 'banner_component',
                    ],
                ]),
            ),
        );
    }

    public function testMapOptionsWithoutParameter(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_component',
                'block_prefix' => 'ngcb_sylius_component',
                'required' => false,
                'custom_params' => [
                    'component_type_identifier' => false,
                ],
            ],
            $this->mapper->mapOptions(ParameterDefinition::fromArray(['type' => new ParameterType(), 'isRequired' => false])),
        );
    }

    public function testMapOptionsWithParameterNull(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_component',
                'block_prefix' => 'ngcb_sylius_component',
                'required' => false,
                'custom_params' => [
                    'component_type_identifier' => false,
                ],
            ],
            $this->mapper->mapOptions(
                ParameterDefinition::fromArray([
                    'type' => new ParameterType(),
                    'isRequired' => false,
                    'options' => [
                        'component_type_identifier' => null,
                    ],
                ]),
            ),
        );
    }
}
