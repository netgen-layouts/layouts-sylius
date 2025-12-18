<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\ConditionType;

use Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Locale;
use Netgen\Layouts\Sylius\Tests\Stubs\Locale as LocaleStub;
use Netgen\Layouts\Sylius\Tests\TestCase\ValidatorTestCaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[CoversClass(Locale::class)]
final class LocaleTest extends TestCase
{
    use ValidatorTestCaseTrait;

    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Resource\Doctrine\Persistence\RepositoryInterface<\Sylius\Component\Locale\Model\LocaleInterface>
     */
    private Stub&RepositoryInterface $repositoryStub;

    private Locale $conditionType;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(RepositoryInterface::class);

        $this->conditionType = new Locale();
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_locale', $this->conditionType::getType());
    }

    public function testValidationValid(): void
    {
        $locale = new LocaleStub(5, 'en_US');

        $this->repositoryStub
            ->method('findOneBy')
            ->with(self::identicalTo(['code' => 'en_US']))
            ->willReturn($locale);

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(['en_US'], $this->conditionType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalidNoLocale(): void
    {
        $this->repositoryStub
            ->method('findOneBy')
            ->with(self::identicalTo(['code' => 'fr_FR']))
            ->willReturn(null);

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(['fr_FR'], $this->conditionType->getConstraints());
        self::assertCount(1, $errors);
    }

    public function testValidationInvalidValue(): void
    {
        $validator = $this->createValidator($this->repositoryStub);

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate([5], $this->conditionType->getConstraints());
    }

    public function testMatches(): void
    {
        $request = Request::create('/');
        $request->setLocale('en_US');

        self::assertTrue($this->conditionType->matches($request, ['en_US']));
        self::assertTrue($this->conditionType->matches($request, ['en_US', 'de_DE']));
        self::assertFalse($this->conditionType->matches($request, ['it_IT']));
        self::assertFalse($this->conditionType->matches($request, ['it_IT', 'fr_FR']));
    }
}
