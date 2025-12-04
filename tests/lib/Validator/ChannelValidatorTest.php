<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use Netgen\Layouts\Sylius\Validator\ChannelValidator;
use Netgen\Layouts\Sylius\Validator\Constraint\Channel;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function sprintf;

#[CoversClass(ChannelValidator::class)]
final class ChannelValidatorTest extends ValidatorTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Component\Channel\Repository\ChannelRepositoryInterface<\Sylius\Component\Channel\Model\ChannelInterface>
     */
    private Stub&ChannelRepositoryInterface $repositoryStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Channel();
    }

    public function getValidator(): ConstraintValidatorInterface
    {
        $this->repositoryStub = self::createStub(ChannelRepositoryInterface::class);

        return new ChannelValidator($this->repositoryStub);
    }

    public function testValidateValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new ChannelStub(42, 'WEBSHOP', 'Webshop'));

        $this->assertValid(true, 42);
    }

    public function testValidateNull(): void
    {
        $this->assertValid(true, null);
    }

    public function testValidateInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $this->assertValid(false, 42);
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage(sprintf('Expected argument of type "%s", "%s" given', Channel::class, NotBlank::class));

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
