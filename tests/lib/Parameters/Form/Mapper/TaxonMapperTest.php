<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\Form\Mapper\TaxonMapper;
use Netgen\Layouts\Sylius\Parameters\ParameterType\TaxonType as ParameterType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

#[CoversClass(TaxonMapper::class)]
final class TaxonMapperTest extends TestCase
{
    private TaxonMapper $mapper;

    private MockObject&TaxonRepositoryInterface $repositoryMock;

    protected function setUp(): void
    {
        $this->mapper = new TaxonMapper();
        $this->repositoryMock = $this->createMock(TaxonRepositoryInterface::class);
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
                'type' => new ParameterType($this->repositoryMock),
                'isRequired' => false,
            ])),
        );
    }
}
