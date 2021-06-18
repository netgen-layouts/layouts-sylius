<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\ConditionType;

use Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Channel;
use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use Netgen\Layouts\Sylius\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validation;

final class ChannelTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Component\Channel\Context\ChannelContextInterface
     */
    private MockObject $channelContextMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Component\Channel\Repository\ChannelRepositoryInterface
     */
    private MockObject $channelRepositoryMock;

    private Channel $conditionType;

    protected function setUp(): void
    {
        $this->channelContextMock = $this->createMock(ChannelContextInterface::class);
        $this->channelRepositoryMock = $this->createMock(ChannelRepositoryInterface::class);

        $this->conditionType = new Channel($this->channelContextMock);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Channel::getType
     */
    public function testGetType(): void
    {
        self::assertSame('sylius_channel', $this->conditionType::getType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Channel::getConstraints
     */
    public function testValidationValid(): void
    {
        $this->channelRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new ChannelStub(42, 'webshop'));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->channelRepositoryMock))
            ->getValidator();

        $errors = $validator->validate([42], $this->conditionType->getConstraints());
        self::assertCount(0, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Channel::getConstraints
     */
    public function testValidationInvalidNoChannel(): void
    {
        $this->channelRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->channelRepositoryMock))
            ->getValidator();

        $errors = $validator->validate([42], $this->conditionType->getConstraints());
        self::assertCount(1, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Channel::getConstraints
     */
    public function testValidationInvalidValue(): void
    {
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->channelRepositoryMock))
            ->getValidator();

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate(['webshop'], $this->conditionType->getConstraints());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Channel::matches
     */
    public function testMatches(): void
    {
        $request = Request::create('/');

        $this->channelContextMock
            ->expects(self::exactly(2))
            ->method('getChannel')
            ->willReturn(new ChannelStub(42, 'webshop'));

        self::assertTrue($this->conditionType->matches($request, [42]));
        self::assertTrue($this->conditionType->matches($request, [42, 43, 44]));
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Channel::matches
     */
    public function testMatchesWithNoChannel(): void
    {
        $request = Request::create('/');

        $this->channelContextMock
            ->expects(self::once())
            ->method('getChannel')
            ->willThrowException(new ChannelNotFoundException());

        self::assertFalse($this->conditionType->matches($request, [42]));
    }
}
