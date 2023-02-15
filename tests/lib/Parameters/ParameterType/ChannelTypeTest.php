<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\ParameterType\ChannelType;
use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use Netgen\Layouts\Sylius\Tests\Validator\RepositoryValidatorFactory;
use Netgen\Layouts\Tests\Parameters\ParameterType\ParameterTypeTestTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validation;

final class ChannelTypeTest extends TestCase
{
    use ParameterTypeTestTrait;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Component\Channel\Repository\ChannelRepositoryInterface
     */
    private MockObject $repositoryMock;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(ChannelRepositoryInterface::class);

        $this->type = new ChannelType();
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Parameters\ParameterType\ChannelType::getIdentifier
     */
    public function testGetIdentifier(): void
    {
        self::assertSame('sylius_channel', $this->type::getIdentifier());
    }

    /**
     * @param mixed[] $options
     * @param mixed[] $resolvedOptions
     *
     * @covers \Netgen\Layouts\Sylius\Parameters\ParameterType\ChannelType::configureOptions
     *
     * @dataProvider validOptionsDataProvider
     */
    public function testValidOptions(array $options, array $resolvedOptions): void
    {
        $parameter = $this->getParameterDefinition($options);
        self::assertSame($resolvedOptions, $parameter->getOptions());
    }

    /**
     * @param mixed[] $options
     *
     * @covers \Netgen\Layouts\Sylius\Parameters\ParameterType\ChannelType::configureOptions
     *
     * @dataProvider invalidOptionsDataProvider
     */
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
                [],
                [
                    'multiple' => false,
                ],
            ],
            [
                [
                    'multiple' => true,
                ],
                [
                    'multiple' => true,
                ],
            ],
            [
                [
                    'multiple' => false,
                ],
                [
                    'multiple' => false,
                ],
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
                [
                    'multiple' => 'Value',
                ],
            ],
        ];
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Parameters\ParameterType\ChannelType::getValueConstraints
     */
    public function testValidationValid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new ChannelStub(42, 'WEBSHOP', 'Webshop'));

        $parameter = $this->getParameterDefinition([], true);
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate([42], $this->type->getConstraints($parameter, 42));
        self::assertCount(0, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Parameters\ParameterType\ChannelType::getValueConstraints
     */
    public function testValidationValidWithNonRequiredValue(): void
    {
        $this->repositoryMock
                ->expects(self::never())
                ->method('find');

        $parameter = $this->getParameterDefinition([], false);
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(null, $this->type->getConstraints($parameter, null));
        self::assertCount(0, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Parameters\ParameterType\ChannelType::getValueConstraints
     */
    public function testValidationInvalid(): void
    {
        $this->repositoryMock
                ->expects(self::once())
                ->method('find')
                ->with(self::identicalTo(42))
                ->willReturn(null);

        $parameter = $this->getParameterDefinition([], true);
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate([42], $this->type->getConstraints($parameter, 42));
        self::assertNotCount(0, $errors);
    }

    /**
     * @param mixed $value
     *
     * @covers \Netgen\Layouts\Sylius\Parameters\ParameterType\ChannelType::isValueEmpty
     *
     * @dataProvider emptyDataProvider
     */
    public function testIsValueEmpty($value, bool $isEmpty): void
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
            [new ChannelStub(42, 'WEBSHOP', 'Webshop'), false],
        ];
    }
}
