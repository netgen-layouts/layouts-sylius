<?php

namespace Netgen\BlockManager\Sylius\Tests\Security\Authorization\Voter;

use Netgen\BlockManager\Sylius\Security\Authorization\Voter\AdminAccessVoter;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdminUser;
use Sylius\Component\User\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AdminAccessVoterTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Sylius\Security\Authorization\Voter\AdminAccessVoter
     */
    private $voter;

    public function setUp()
    {
        $this->voter = new AdminAccessVoter();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Security\Authorization\Voter\AdminAccessVoter::vote
     */
    public function testVote()
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(new AdminUser()));

        $this->assertEquals(1, $this->voter->vote($token, null, array()));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Security\Authorization\Voter\AdminAccessVoter::vote
     */
    public function testVoteWithoutAdminUser()
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(new User()));

        $this->assertEquals(0, $this->voter->vote($token, null, array()));
    }
}
