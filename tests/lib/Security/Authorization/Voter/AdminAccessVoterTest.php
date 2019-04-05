<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Security\Authorization\Voter;

use Netgen\Layouts\Sylius\Security\Authorization\Voter\AdminAccessVoter;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdminUser;
use Sylius\Component\User\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class AdminAccessVoterTest extends TestCase
{
    /**
     * @var \Netgen\Layouts\Sylius\Security\Authorization\Voter\AdminAccessVoter
     */
    private $voter;

    public function setUp(): void
    {
        $this->voter = new AdminAccessVoter();
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Security\Authorization\Voter\AdminAccessVoter::vote
     */
    public function testVote(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects(self::any())
            ->method('getUser')
            ->willReturn(new AdminUser());

        self::assertSame(1, $this->voter->vote($token, null, []));
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Security\Authorization\Voter\AdminAccessVoter::vote
     */
    public function testVoteWithoutAdminUser(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects(self::any())
            ->method('getUser')
            ->willReturn(new User());

        self::assertSame(0, $this->voter->vote($token, null, []));
    }
}
