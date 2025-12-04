<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\Form\Mapper\TaxonMapper;
use Netgen\Layouts\Sylius\Parameters\ParameterType\TaxonType as ParameterType;
use Netgen\Layouts\Sylius\Repository\TaxonRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(TaxonMapper::class)]
final class TaxonMapperTest extends TestCase
{
    private TaxonMapper $mapper;

    private Stub&TaxonRepositoryInterface $repositoryStub;

    protected function setUp(): void
    {
        $this->mapper = new TaxonMapper();
        $this->repositoryStub = self::createStub(TaxonRepositoryInterface::class);
    }

    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserType::class, $this->mapper->getFormType());
    }

    public function testMapOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_taxon',
                'required' => false,
            ],
            $this->mapper->mapOptions(ParameterDefinition::fromArray([
                'type' => new ParameterType($this->repositoryStub),
                'isRequired' => false,
            ])),
        );
    }
}
