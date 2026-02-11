<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\Layouts\Sylius\Layout\Resolver\TargetType\TaxonProduct;
use Netgen\Layouts\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\Layouts\Sylius\Tests\TestCase\ValidatorTestCaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\ProductTaxon;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(TaxonProduct::class)]
final class TaxonProductTest extends TestCase
{
    use ValidatorTestCaseTrait;

    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface<\Sylius\Component\Taxonomy\Model\TaxonInterface>
     */
    private Stub&TaxonRepositoryInterface $repositoryStub;

    private TaxonProduct $targetType;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(TaxonRepositoryInterface::class);

        $this->targetType = new TaxonProduct($this->repositoryStub);
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_taxon_product', $this->targetType::getType());
    }

    public function testValidationValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(new TaxonStub(42));

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    public function testProvideValue(): void
    {
        $product = new ProductStub(42);
        foreach ([12, 13] as $taxonId) {
            $productTaxon = new ProductTaxon();
            $productTaxon->setTaxon(new TaxonStub($taxonId));

            $product->addProductTaxon($productTaxon);
        }

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_resource', $product);

        self::assertSame([12, 13], $this->targetType->provideValue($request));
    }

    public function testProvideValueWithNoTaxon(): void
    {
        $request = Request::create('/');

        self::assertNull($this->targetType->provideValue($request));
    }

    public function testGetValueObject(): void
    {
        $stub = new TaxonStub(1);

        $this->repositoryStub
            ->method('find')
            ->willReturn($stub);

        self::assertSame($stub, $this->targetType->getValueObject(1));
    }

    public function testGetValueObjectWithNoTaxon(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->targetType->getValueObject(1));
    }
}
