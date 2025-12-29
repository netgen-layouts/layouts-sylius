<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Taxon;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\Layouts\Sylius\Tests\TestCase\ValidatorTestCaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(Taxon::class)]
final class TaxonTest extends TestCase
{
    use ValidatorTestCaseTrait;

    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface<\Sylius\Component\Taxonomy\Model\TaxonInterface>
     */
    private Stub&TaxonRepositoryInterface $repositoryStub;

    private Taxon $targetType;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(TaxonRepositoryInterface::class);

        $this->targetType = new Taxon($this->repositoryStub);
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_taxon', $this->targetType::getType());
    }

    public function testValidationValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new TaxonStub(42));

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    public function testProvideValue(): void
    {
        $taxon = new TaxonStub(42);
        $taxon->setParent(new TaxonStub(24));

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_resource', $taxon);

        self::assertSame(42, $this->targetType->provideValue($request));
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
