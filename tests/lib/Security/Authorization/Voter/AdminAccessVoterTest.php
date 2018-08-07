<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Security\Authorization\Voter;

use Netgen\BlockManager\Sylius\Security\Authorization\Voter\AdminAccessVoter;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdminUser;
use Sylius\Component\User\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class AdminAccessVoterTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Sylius\Security\Authorization\Voter\AdminAccessVoter
     */
    private $voter;

    public function setUp(): void
    {
        $this->voter = new AdminAccessVoter();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Security\Authorization\Voter\AdminAccessVoter::vote
     */
    public function testVote(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->will(self::returnValue(new AdminUser()));

        self::assertSame(1, $this->voter->vote($token, null, []));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Security\Authorization\Voter\AdminAccessVoter::vote
     */
    public function testVoteWithoutAdminUser(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->will(self::returnValue(new User()));

        self::assertSame(0, $this->voter->vote($token, null, []));
    }
}
