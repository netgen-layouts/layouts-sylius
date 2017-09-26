<?php

namespace Netgen\BlockManager\Sylius\Security\Authorization\Voter;

use Sylius\Component\Core\Model\AdminUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Votes on Netgen Layouts attributes (ROLE_NGBM_*) by matching corresponding access
 * rights in Sylius eCommerce.
 */
final class AdminAccessVoter implements VoterInterface
{
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if ($token->getUser() instanceof AdminUserInterface) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
