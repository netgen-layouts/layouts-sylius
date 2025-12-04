<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\Item\ValueUrlGenerator\TaxonValueUrlGenerator;
use Netgen\Layouts\Sylius\Tests\Item\Stubs\Taxon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(TaxonValueUrlGenerator::class)]
final class TaxonValueUrlGeneratorTest extends TestCase
{
    private Stub&UrlGeneratorInterface $urlGeneratorStub;

    private TaxonValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorStub = self::createStub(UrlGeneratorInterface::class);

        $this->urlGenerator = new TaxonValueUrlGenerator($this->urlGeneratorStub);
    }

    public function testGenerateDefaultUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->with(
                self::identicalTo('sylius_shop_product_index'),
                self::identicalTo(['slug' => 'taxon-name']),
            )
            ->willReturn('/_partial/taxons/by-slug/taxon-name');

        self::assertSame(
            '/_partial/taxons/by-slug/taxon-name',
            $this->urlGenerator->generateDefaultUrl(new Taxon(42, 'Taxon name', 'taxon-name')),
        );
    }

    public function testGenerateAdminUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->with(
                self::identicalTo('sylius_admin_taxon_update'),
                self::identicalTo(['id' => 42]),
            )
            ->willReturn('/admin/taxons/42/edit');

        self::assertSame(
            '/admin/taxons/42/edit',
            $this->urlGenerator->generateAdminUrl(new Taxon(42, 'Taxon name', 'taxon-name')),
        );
    }
}
