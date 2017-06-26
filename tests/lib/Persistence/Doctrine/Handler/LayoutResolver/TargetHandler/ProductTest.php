<?php

namespace Netgen\BlockManager\Sylius\Tests\Persistence\Doctrine\Handler\LayoutResolver\TargetHandler;

use Netgen\BlockManager\Sylius\Persistence\Doctrine\QueryHandler\LayoutResolver\TargetHandler\Product;
use Netgen\BlockManager\Tests\Persistence\Doctrine\Handler\LayoutResolver\TargetHandler\AbstractTargetHandlerTest;

class ProductTest extends AbstractTargetHandlerTest
{
    /**
     * @covers \Netgen\BlockManager\Persistence\Doctrine\Handler\LayoutResolverHandler::matchRules
     * @covers \Netgen\BlockManager\Persistence\Doctrine\QueryHandler\LayoutResolverQueryHandler::matchRules
     * @covers \Netgen\BlockManager\Sylius\Persistence\Doctrine\QueryHandler\LayoutResolver\TargetHandler\Product::handleQuery
     */
    public function testMatchRules()
    {
        $rules = $this->handler->matchRules($this->getTargetIdentifier(), 72);

        $this->assertCount(1, $rules);
        $this->assertEquals(1, $rules[0]->id);
    }

    /**
     * Returns the target identifier under test.
     *
     * @return string
     */
    protected function getTargetIdentifier()
    {
        return 'sylius_product';
    }

    /**
     * Creates the handler under test.
     *
     * @return \Netgen\BlockManager\Persistence\Doctrine\QueryHandler\LayoutResolver\TargetHandler
     */
    protected function getTargetHandler()
    {
        return new Product();
    }

    /**
     * Inserts database fixtures.
     *
     * @param string $fixturesPath
     */
    protected function insertDatabaseFixtures($fixturesPath)
    {
        parent::insertDatabaseFixtures(__DIR__ . '/../../../../../../_fixtures');
    }
}
