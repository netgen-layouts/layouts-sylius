<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\ConditionType;

use Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Channel;
use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use Netgen\Layouts\Sylius\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validation;

#[CoversClass(Channel::class)]
final class ChannelTest extends TestCase
{
    private ChannelContextInterface&MockObject $channelContextMock;

    private ChannelRepositoryInterface&MockObject $channelRepositoryMock;

    private Channel $conditionType;

    protected function setUp(): void
    {
        $this->channelContextMock = $this->createMock(ChannelContextInterface::class);
        $this->channelRepositoryMock = $this->createMock(ChannelRepositoryInterface::class);

        $this->conditionType = new Channel($this->channelContextMock);
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_channel', $this->conditionType::getType());
    }

    public function testValidationValid(): void
    {
        $this->channelRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new ChannelStub(42, 'WEBSHOP', 'Webshop'));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->channelRepositoryMock))
            ->getValidator();

        $errors = $validator->validate([42], $this->conditionType->getConstraints());
        self::assertCount(0, $errors);
    }

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

    public function testValidationInvalidValue(): void
    {
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->channelRepositoryMock))
            ->getValidator();

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate(['webshop'], $this->conditionType->getConstraints());
    }

    public function testMatches(): void
    {
        $request = Request::create('/');

        $this->channelContextMock
            ->expects(self::exactly(2))
            ->method('getChannel')
            ->willReturn(new ChannelStub(42, 'WEBSHOP', 'Webshop'));

        self::assertTrue($this->conditionType->matches($request, [42]));
        self::assertTrue($this->conditionType->matches($request, [42, 43, 44]));
    }

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
