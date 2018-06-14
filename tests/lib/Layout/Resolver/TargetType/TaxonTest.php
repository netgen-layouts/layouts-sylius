<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Taxon;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\BlockManager\Sylius\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

final class TaxonTest extends TestCase
{
    /**
     * @var \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    /**
     * @var \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Taxon
     */
    private $targetType;

    public function setUp(): void
    {
        $this->repositoryMock = $this->createMock(TaxonRepositoryInterface::class);

        $this->targetType = new Taxon();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Taxon::getType
     */
    public function testGetType(): void
    {
        $this->assertEquals('sylius_taxon', $this->targetType->getType());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Taxon::getConstraints
     */
    public function testValidationValid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue(new TaxonStub(42)));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        $this->assertTrue($errors->count() === 0);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Taxon::getConstraints
     */
    public function testValidationInvalid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue(null));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        $this->assertFalse($errors->count() === 0);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Taxon::provideValue
     */
    public function testProvideValue(): void
    {
        $taxon = new TaxonStub(42);
        $taxon->setParent(new TaxonStub(24));

        $request = Request::create('/');
        $request->attributes->set('ngbm_sylius_taxon', $taxon);

        $this->assertEquals([42, 24], $this->targetType->provideValue($request));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Taxon::provideValue
     */
    public function testProvideValueWithNoTaxon(): void
    {
        $request = Request::create('/');

        $this->assertNull($this->targetType->provideValue($request));
    }
}
