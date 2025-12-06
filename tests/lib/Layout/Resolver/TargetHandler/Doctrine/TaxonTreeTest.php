<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetHandler\Doctrine;

use Netgen\Layouts\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;
use Netgen\Layouts\Persistence\Values\LayoutResolver\RuleGroup;
use Netgen\Layouts\Persistence\Values\Status;
use Netgen\Layouts\Sylius\Layout\Resolver\TargetHandler\Doctrine\TaxonTree;
use Netgen\Layouts\Tests\Layout\Resolver\TargetHandler\Doctrine\TargetHandlerTestBase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TaxonTree::class)]
final class TaxonTreeTest extends TargetHandlerTestBase
{
    public function testMatchRules(): void
    {
        $rules = $this->handler->matchRules(
            $this->handler->loadRuleGroup(RuleGroup::ROOT_UUID, Status::Published),
            $this->getTargetIdentifier(),
            [1, 2, 42],
        );

        self::assertCount(1, $rules);
        self::assertSame(4, $rules[0]->id);
    }

    protected function getTargetIdentifier(): string
    {
        return 'sylius_taxon_tree';
    }

    protected function getTargetHandler(): TargetHandlerInterface
    {
        return new TaxonTree();
    }

    protected function provideFixturesPath(): string
    {
        return __DIR__ . '/../../../../../_fixtures';
    }
}
