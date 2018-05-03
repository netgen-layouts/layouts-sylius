<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Validator;

use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\BlockManager\Sylius\Validator\Constraint\Taxon;
use Netgen\BlockManager\Sylius\Validator\TaxonValidator;
use Netgen\BlockManager\Tests\TestCase\ValidatorTestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

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

    public function getValidator(): TaxonValidator
    {
        $this->repositoryMock = $this->createMock(TaxonRepositoryInterface::class);

        return new TaxonValidator($this->repositoryMock);
    }

    /**
     * @param int|null $taxonId
     * @param bool $isValid
     *
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::validate
     * @dataProvider validateDataProvider
     */
    public function testValidate($taxonId, bool $isValid): void
    {
        if ($taxonId !== null) {
            $this->repositoryMock
                ->expects($this->once())
                ->method('find')
                ->with($this->equalTo($taxonId))
                ->will(
                    $this->returnCallback(
                        function () use ($taxonId) {
                            if (!is_int($taxonId) || $taxonId <= 0 || $taxonId > 20) {
                                return;
                            }

                            return new TaxonStub($taxonId);
                        }
                    )
                );
        }

        $this->assertValid($isValid, $taxonId);
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

    public function validateDataProvider(): array
    {
        return [
            [12, true],
            [25, false],
            [-12, false],
            [0, false],
            ['12', false],
            [null, true],
        ];
    }
}
