<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\Layouts\Sylius\Layout\Resolver\TargetType\TaxonTree;
use Netgen\Layouts\Sylius\Repository\TaxonRepositoryInterface;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\Layouts\Sylius\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

#[CoversClass(TaxonTree::class)]
final class TaxonTreeTest extends TestCase
{
    private Stub&TaxonRepositoryInterface $repositoryStub;

    private TaxonTree $targetType;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(TaxonRepositoryInterface::class);

        $this->targetType = new TaxonTree($this->repositoryStub);
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_taxon_tree', $this->targetType::getType());
    }

    public function testValidationValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new TaxonStub(42));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryStub))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryStub))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    public function testProvideValue(): void
    {
        $taxon = new TaxonStub(42);
        $taxon->setParent(new TaxonStub(24));

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_resource', $taxon);

        self::assertSame([42, 24], $this->targetType->provideValue($request));
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
            ->with(self::identicalTo(1))
            ->willReturn($stub);

        self::assertSame($stub, $this->targetType->getValueObject(1));
    }

    public function testGetValueObjectWithNoTaxon(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(1))
            ->willReturn(null);

        self::assertNull($this->targetType->getValueObject(1));
    }
}
