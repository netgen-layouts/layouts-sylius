<?php

namespace Netgen\BlockManager\Sylius\Tests\Layout\Resolver\TargetHandler\Doctrine;

use Netgen\BlockManager\Sylius\Layout\Resolver\TargetHandler\Doctrine\Taxon;
use Netgen\BlockManager\Tests\Layout\Resolver\TargetHandler\Doctrine\AbstractTargetHandlerTest;

class TaxonTest extends AbstractTargetHandlerTest
{
    /**
     * @covers \Netgen\BlockManager\Persistence\Doctrine\Handler\LayoutResolverHandler::matchRules
     * @covers \Netgen\BlockManager\Persistence\Doctrine\QueryHandler\LayoutResolverQueryHandler::matchRules
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetHandler\Doctrine\Taxon::handleQuery
     */
    public function testMatchRules()
    {
        $rules = $this->handler->matchRules($this->getTargetIdentifier(), array(1, 2, 42));

        $this->assertCount(1, $rules);
        $this->assertEquals(4, $rules[0]->id);
    }

    /**
     * Returns the target identifier under test.
     *
     * @return string
     */
    protected function getTargetIdentifier()
    {
        return 'sylius_taxon';
    }

    /**
     * Creates the handler under test.
     *
     * @return \Netgen\BlockManager\Layout\Resolver\TargetHandler\Doctrine\TargetHandlerInterface
     */
    protected function getTargetHandler()
    {
        return new Taxon();
    }

    /**
     * Inserts database fixtures.
     *
     * @param string $fixturesPath
     */
    protected function insertDatabaseFixtures($fixturesPath)
    {
        parent::insertDatabaseFixtures(__DIR__ . '/../../../../../_fixtures');
    }
}
