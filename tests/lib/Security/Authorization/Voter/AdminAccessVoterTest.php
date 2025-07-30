<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Security\Authorization\Voter;

use Netgen\Layouts\Sylius\Security\Authorization\Voter\AdminAccessVoter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdminUser;
use Sylius\Component\User\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

#[CoversClass(AdminAccessVoter::class)]
final class AdminAccessVoterTest extends TestCase
{
    private AdminAccessVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new AdminAccessVoter();
    }

    public function testVote(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token
            ->method('getUser')
            ->willReturn(new AdminUser());

        self::assertSame(VoterInterface::ACCESS_GRANTED, $this->voter->vote($token, null, ['nglayouts:foo:bar']));
    }

    public function testVoteWithoutAdminUser(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token
            ->method('getUser')
            ->willReturn(new User());

        self::assertSame(VoterInterface::ACCESS_DENIED, $this->voter->vote($token, null, ['nglayouts:foo:bar']));
    }
}
