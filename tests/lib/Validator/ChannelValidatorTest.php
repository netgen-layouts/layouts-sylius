<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use Netgen\Layouts\Sylius\Validator\ChannelValidator;
use Netgen\Layouts\Sylius\Validator\Constraint\Channel;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[CoversClass(ChannelValidator::class)]
final class ChannelValidatorTest extends ValidatorTestCase
{
    private ChannelRepositoryInterface&MockObject $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Channel();
    }

    public function getValidator(): ConstraintValidatorInterface
    {
        $this->repositoryMock = $this->createMock(ChannelRepositoryInterface::class);

        return new ChannelValidator($this->repositoryMock);
    }

    public function testValidateValid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new ChannelStub(42, 'WEBSHOP', 'Webshop'));

        $this->assertValid(true, 42);
    }

    public function testValidateNull(): void
    {
        $this->repositoryMock
            ->expects(self::never())
            ->method('find');

        $this->assertValid(true, null);
    }

    public function testValidateInvalid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $this->assertValid(false, 42);
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Netgen\\Layouts\\Sylius\\Validator\\Constraint\\Channel", "Symfony\\Component\\Validator\\Constraints\\NotBlank" given');

        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "numeric", "array" given');

        $this->assertValid(true, []);
    }
}
