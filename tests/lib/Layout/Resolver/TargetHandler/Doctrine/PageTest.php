<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetHandler\Doctrine;

use Netgen\Layouts\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;
use Netgen\Layouts\Persistence\Values\LayoutResolver\RuleGroup;
use Netgen\Layouts\Persistence\Values\Value;
use Netgen\Layouts\Sylius\Layout\Resolver\TargetHandler\Doctrine\Page;
use Netgen\Layouts\Tests\Layout\Resolver\TargetHandler\Doctrine\TargetHandlerTestBase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Page::class)]
final class PageTest extends TargetHandlerTestBase
{
    public function testMatchRules(): void
    {
        $rules = $this->handler->matchRules(
            $this->handler->loadRuleGroup(RuleGroup::ROOT_UUID, Value::STATUS_PUBLISHED),
            $this->getTargetIdentifier(),
            'homepage',
        );

        self::assertCount(2, $rules);
        self::assertSame(15, $rules[0]->id);
        self::assertSame(17, $rules[1]->id);
    }

    protected function getTargetIdentifier(): string
    {
        return 'sylius_page';
    }

    protected function getTargetHandler(): TargetHandlerInterface
    {
        return new Page();
    }

    protected function insertDatabaseFixtures(string $fixturesPath): void
    {
        parent::insertDatabaseFixtures(__DIR__ . '/../../../../../_fixtures/data.php');
    }
}
