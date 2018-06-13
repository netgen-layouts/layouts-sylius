<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Validator;

use Netgen\BlockManager\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\BlockManager\Sylius\Validator\Constraint\Product;
use Netgen\BlockManager\Sylius\Validator\ProductValidator;
use Netgen\BlockManager\Tests\TestCase\ValidatorTestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ProductValidatorTest extends ValidatorTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Product();
    }

    public function getValidator(): ProductValidator
    {
        $this->repositoryMock = $this->createMock(ProductRepositoryInterface::class);

        return new ProductValidator($this->repositoryMock);
    }

    /**
     * @param int|null $productId
     * @param bool $isValid
     *
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     * @dataProvider validateDataProvider
     */
    public function testValidate($productId, bool $isValid): void
    {
        if ($productId !== null) {
            $this->repositoryMock
                ->expects($this->once())
                ->method('find')
                ->with($this->equalTo($productId))
                ->will(
                    $this->returnCallback(
                        function () use ($productId): ?ProductStub {
                            if (!is_int($productId) || $productId <= 0 || $productId > 20) {
                                return null;
                            }

                            return new ProductStub($productId);
                        }
                    )
                );
        }

        $this->assertValid($isValid, $productId);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Netgen\BlockManager\Sylius\Validator\Constraint\Product", "Symfony\Component\Validator\Constraints\NotBlank" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
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
