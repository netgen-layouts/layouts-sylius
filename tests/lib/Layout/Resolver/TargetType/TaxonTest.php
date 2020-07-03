<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Taxon;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\Layouts\Sylius\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

final class TaxonTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface
     */
    private $repositoryMock;

    /**
     * @var \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Taxon
     */
    private $targetType;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(TaxonRepositoryInterface::class);

        $this->targetType = new Taxon();
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Taxon::getType
     */
    public function testGetType(): void
    {
        self::assertSame('sylius_taxon', $this->targetType::getType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Taxon::getConstraints
     */
    public function testValidationValid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new TaxonStub(42));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Taxon::getConstraints
     */
    public function testValidationInvalid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Taxon::provideValue
     */
    public function testProvideValue(): void
    {
        $taxon = new TaxonStub(42);
        $taxon->setParent(new TaxonStub(24));

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_taxon', $taxon);

        self::assertSame([42, 24], $this->targetType->provideValue($request));
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Taxon::provideValue
     */
    public function testProvideValueWithNoTaxon(): void
    {
        $request = Request::create('/');

        self::assertNull($this->targetType->provideValue($request));
    }
}
