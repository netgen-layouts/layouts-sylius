<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Validator;

use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\BlockManager\Sylius\Validator\Constraint\Taxon;
use Netgen\BlockManager\Sylius\Validator\TaxonValidator;
use Netgen\BlockManager\Tests\TestCase\ValidatorTestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;

final class TaxonValidatorTest extends ValidatorTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Taxon();
    }

    public function getValidator(): ConstraintValidatorInterface
    {
        $this->repositoryMock = $this->createMock(TaxonRepositoryInterface::class);

        return new TaxonValidator($this->repositoryMock);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::validate
     */
    public function testValidateValid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->identicalTo(42))
            ->will($this->returnValue(new TaxonStub(42)));

        $this->assertValid(true, 42);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::validate
     */
    public function testValidateNull(): void
    {
        $this->repositoryMock
            ->expects($this->never())
            ->method('find');

        $this->assertValid(true, null);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::validate
     */
    public function testValidateInvalid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->identicalTo(42))
            ->will($this->returnValue(null));

        $this->assertValid(false, 42);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Netgen\BlockManager\Sylius\Validator\Constraint\Taxon", "Symfony\Component\Validator\Constraints\NotBlank" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "scalar", "array" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->assertValid(true, []);
    }
}
