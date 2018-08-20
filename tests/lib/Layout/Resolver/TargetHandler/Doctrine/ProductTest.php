<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetHandler\Doctrine;

use Netgen\BlockManager\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;
use Netgen\BlockManager\Tests\Layout\Resolver\TargetHandler\Doctrine\AbstractTargetHandlerTest;
use Netgen\Layouts\Sylius\Layout\Resolver\TargetHandler\Doctrine\Product;

final class ProductTest extends AbstractTargetHandlerTest
{
    /**
     * @covers \Netgen\BlockManager\Persistence\Doctrine\Handler\LayoutResolverHandler::matchRules
     * @covers \Netgen\BlockManager\Persistence\Doctrine\QueryHandler\LayoutResolverQueryHandler::matchRules
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetHandler\Doctrine\Product::handleQuery
     */
    public function testMatchRules(): void
    {
        $rules = $this->handler->matchRules($this->getTargetIdentifier(), 72);

        self::assertCount(1, $rules);
        self::assertSame(1, $rules[0]->id);
    }

    protected function getTargetIdentifier(): string
    {
        return 'sylius_product';
    }

    protected function getTargetHandler(): TargetHandlerInterface
    {
        return new Product();
    }

    protected function insertDatabaseFixtures(string $fixturesPath): void
    {
        parent::insertDatabaseFixtures(__DIR__ . '/../../../../../_fixtures');
    }
}
