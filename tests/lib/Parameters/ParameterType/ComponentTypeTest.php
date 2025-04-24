<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\ParameterType;

use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\ParameterType\ComponentType;
use Netgen\Layouts\Tests\Parameters\ParameterType\ParameterTypeTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Netgen\Layouts\Sylius\Tests\Stubs\Product as ProductStub;

#[CoversClass(ComponentType::class)]
final class ComponentTypeTest extends TestCase
{
    use ParameterTypeTestTrait;

    private MockObject&ComponentRepositoryInterface $repositoryMock;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(ComponentRepositoryInterface::class);

        $this->type = new ComponentType();
    }

    public function testGetIdentifier(): void
    {
        self::assertSame('sylius_component', $this->type::getIdentifier());
    }

    /**
     * @param mixed[] $options
     * @param mixed[] $resolvedOptions
     */
    #[DataProvider('validOptionsDataProvider')]
    public function testValidOptions(array $options, array $resolvedOptions): void
    {
        $parameter = $this->getParameterDefinition($options);
        self::assertSame($resolvedOptions, $parameter->getOptions());
    }

    /**
     * @param mixed[] $options
     */
    #[DataProvider('invalidOptionsDataProvider')]
    public function testInvalidOptions(array $options): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getParameterDefinition($options);
    }

    /**
     * Provider for testing valid parameter attributes.
     *
     * @return mixed[]
     */
    public static function validOptionsDataProvider(): array
    {
        return [
            [
                [
                    'component_type_identifier' => 'banner_component',
                ],
                [
                    'component_type_identifier' => 'banner_component',
                ]
            ],
        ];
    }

    /**
     * Provider for testing invalid parameter attributes.
     *
     * @return mixed[]
     */
    public static function invalidOptionsDataProvider(): array
    {
        return [
            [
                [
                    'undefined_value' => 'Value',
                ],
                [],
            ],
        ];
    }

    #[DataProvider('emptyDataProvider')]
    public function testIsValueEmpty(mixed $value, bool $isEmpty): void
    {
        self::assertSame($isEmpty, $this->type->isValueEmpty(new ParameterDefinition(), $value));
    }

    /**
     * @return mixed[]
     */
    public static function emptyDataProvider(): array
    {
        return [
            [null, true],
            [new ProductStub(42), false],
        ];
    }
}
