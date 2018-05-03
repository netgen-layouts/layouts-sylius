<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\TaxonProduct;
use Netgen\BlockManager\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\BlockManager\Sylius\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\ProductTaxon;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

final class TaxonProductTest extends TestCase
{
    /**
     * @var \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    /**
     * @var \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\TaxonProduct
     */
    private $targetType;

    public function setUp(): void
    {
        $this->repositoryMock = $this->createMock(TaxonRepositoryInterface::class);

        $this->targetType = new TaxonProduct();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\TaxonProduct::getType
     */
    public function testGetType(): void
    {
        $this->assertEquals('sylius_taxon_product', $this->targetType->getType());
    }

    /**
     * @param mixed $value
     * @param bool $isValid
     *
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\TaxonProduct::getConstraints
     * @dataProvider validationProvider
     */
    public function testValidation($value, bool $isValid): void
    {
        if ($value !== null) {
            $this->repositoryMock
                ->expects($this->once())
                ->method('find')
                ->with($this->equalTo($value))
                ->will(
                    $this->returnCallback(
                        function () use ($value) {
                            if (!is_int($value) || $value > 20) {
                                return;
                            }

                            return new TaxonStub($value);
                        }
                    )
                );
        }

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate($value, $this->targetType->getConstraints());
        $this->assertEquals($isValid, $errors->count() === 0);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\TaxonProduct::provideValue
     */
    public function testProvideValue(): void
    {
        $product = new ProductStub(42);
        foreach ([12, 13] as $taxonId) {
            $productTaxon = new ProductTaxon();
            $productTaxon->setTaxon(new TaxonStub($taxonId));

            $product->addProductTaxon($productTaxon);
        }

        $request = Request::create('/');
        $request->attributes->set('ngbm_sylius_product', $product);

        $this->assertEquals([12, 13], $this->targetType->provideValue($request));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\TaxonProduct::provideValue
     */
    public function testProvideValueWithNoTaxon(): void
    {
        $request = Request::create('/');

        $this->assertNull($this->targetType->provideValue($request));
    }

    public function validationProvider(): array
    {
        return [
            [12, true],
            [24, false],
            [-12, false],
            [0, false],
            ['12', false],
            ['', false],
            [null, false],
        ];
    }
}
