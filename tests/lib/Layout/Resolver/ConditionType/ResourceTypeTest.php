<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\ConditionType;

use Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\ResourceType;
use Netgen\Layouts\Sylius\Tests\Stubs\Product;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon;
use Netgen\Layouts\Sylius\Tests\Validator\SettingsValidatorFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[CoversClass(ResourceType::class)]
final class ResourceTypeTest extends TestCase
{
    private ResourceType $conditionType;

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $allowedResources = [
            'Sylius\Component\Product\Model\ProductInterface' => 'product',
            'Sylius\Component\Taxonomy\Model\TaxonInterface' => 'taxon',
        ];

        $this->validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new SettingsValidatorFactory($allowedResources))
            ->getValidator();

        $this->conditionType = new ResourceType($allowedResources);
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_resource_type', $this->conditionType::getType());
    }

    public function testValidationValid(): void
    {
        $errors = $this->validator->validate(['product'], $this->conditionType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationNonExisting(): void
    {
        $errors = $this->validator->validate(['category'], $this->conditionType->getConstraints());
        self::assertCount(1, $errors);
    }

    public function testValidationInvalidValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate([5], $this->conditionType->getConstraints());
    }

    public function testMatchesProduct(): void
    {
        $request = Request::create('/');
        $product = new Product(3);
        $request->attributes->set('nglayouts_sylius_resource', $product);

        self::assertTrue($this->conditionType->matches($request, ['product']));
        self::assertTrue($this->conditionType->matches($request, ['product', 'taxon']));
        self::assertFalse($this->conditionType->matches($request, ['taxon']));
        self::assertFalse($this->conditionType->matches($request, ['taxon', 'category']));
    }

    public function testMatchesTaxon(): void
    {
        $request = Request::create('/');
        $taxon = new Taxon(3);
        $request->attributes->set('nglayouts_sylius_resource', $taxon);

        self::assertTrue($this->conditionType->matches($request, ['taxon']));
        self::assertTrue($this->conditionType->matches($request, ['product', 'taxon']));
        self::assertFalse($this->conditionType->matches($request, ['product']));
        self::assertFalse($this->conditionType->matches($request, ['product', 'category']));
    }
}
