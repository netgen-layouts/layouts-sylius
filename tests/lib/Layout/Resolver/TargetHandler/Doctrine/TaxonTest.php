<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Layout\Resolver\TargetHandler\Doctrine;

use Netgen\BlockManager\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;
use Netgen\BlockManager\Sylius\Layout\Resolver\TargetHandler\Doctrine\Taxon;
use Netgen\BlockManager\Tests\Layout\Resolver\TargetHandler\Doctrine\AbstractTargetHandlerTest;

final class TaxonTest extends AbstractTargetHandlerTest
{
    /**
     * @covers \Netgen\BlockManager\Persistence\Doctrine\Handler\LayoutResolverHandler::matchRules
     * @covers \Netgen\BlockManager\Persistence\Doctrine\QueryHandler\LayoutResolverQueryHandler::matchRules
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetHandler\Doctrine\Taxon::handleQuery
     */
    public function testMatchRules(): void
    {
        $rules = $this->handler->matchRules($this->getTargetIdentifier(), [1, 2, 42]);

        self::assertCount(1, $rules);
        self::assertSame(4, $rules[0]->id);
    }

    protected function getTargetIdentifier(): string
    {
        return 'sylius_taxon';
    }

    protected function getTargetHandler(): TargetHandlerInterface
    {
        return new Taxon();
    }

    protected function insertDatabaseFixtures(string $fixturesPath): void
    {
        parent::insertDatabaseFixtures(__DIR__ . '/../../../../../_fixtures');
    }
}
