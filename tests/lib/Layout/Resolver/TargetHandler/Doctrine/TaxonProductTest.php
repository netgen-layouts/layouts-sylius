<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetHandler\Doctrine;

use Netgen\Layouts\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;
use Netgen\Layouts\Persistence\Values\LayoutResolver\RuleGroup;
use Netgen\Layouts\Persistence\Values\Value;
use Netgen\Layouts\Sylius\Layout\Resolver\TargetHandler\Doctrine\TaxonProduct;
use Netgen\Layouts\Tests\Layout\Resolver\TargetHandler\Doctrine\TargetHandlerTestBase;

final class TaxonProductTest extends TargetHandlerTestBase
{
    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetHandler\Doctrine\TaxonProduct::handleQuery
     */
    public function testMatchRules(): void
    {
        $rules = $this->handler->matchRules(
            $this->handler->loadRuleGroup(RuleGroup::ROOT_UUID, Value::STATUS_PUBLISHED),
            $this->getTargetIdentifier(),
            [1, 2, 42],
        );

        self::assertCount(1, $rules);
        self::assertSame(6, $rules[0]->id);
    }

    protected function getTargetIdentifier(): string
    {
        return 'sylius_taxon_product';
    }

    protected function getTargetHandler(): TargetHandlerInterface
    {
        return new TaxonProduct();
    }

    protected function insertDatabaseFixtures(string $fixturesPath): void
    {
        parent::insertDatabaseFixtures(__DIR__ . '/../../../../../_fixtures/data.php');
    }
}
