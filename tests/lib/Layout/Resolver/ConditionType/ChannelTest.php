<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\ConditionType;

use Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Channel;
use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use Netgen\Layouts\Sylius\Tests\TestCase\ValidatorTestCaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[CoversClass(Channel::class)]
final class ChannelTest extends TestCase
{
    use ValidatorTestCaseTrait;

    private Stub&ChannelContextInterface $channelContextStub;

    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Component\Channel\Repository\ChannelRepositoryInterface<\Sylius\Component\Channel\Model\ChannelInterface>
     */
    private Stub&ChannelRepositoryInterface $repositoryStub;

    private Channel $conditionType;

    protected function setUp(): void
    {
        $this->channelContextStub = self::createStub(ChannelContextInterface::class);
        $this->repositoryStub = self::createStub(ChannelRepositoryInterface::class);

        $this->conditionType = new Channel($this->channelContextStub);
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_channel', $this->conditionType::getType());
    }

    public function testValidationValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new ChannelStub(42, 'WEB SHOP', 'Web shop'));

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate([42], $this->conditionType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalidNoChannel(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate([42], $this->conditionType->getConstraints());
        self::assertCount(1, $errors);
    }

    public function testValidationInvalidValue(): void
    {
        $validator = $this->createValidator($this->repositoryStub);

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate(['web shop'], $this->conditionType->getConstraints());
    }

    public function testMatches(): void
    {
        $request = Request::create('/');

        $this->channelContextStub
            ->method('getChannel')
            ->willReturn(new ChannelStub(42, 'WEB SHOP', 'Web shop'));

        self::assertTrue($this->conditionType->matches($request, [42]));
        self::assertTrue($this->conditionType->matches($request, [42, 43, 44]));
    }

    public function testMatchesWithNoChannel(): void
    {
        $request = Request::create('/');

        $this->channelContextStub
            ->method('getChannel')
            ->willThrowException(new ChannelNotFoundException());

        self::assertFalse($this->conditionType->matches($request, [42]));
    }
}
