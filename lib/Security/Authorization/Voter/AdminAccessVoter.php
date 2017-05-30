<?php

namespace Netgen\BlockManager\Sylius\Security\Authorization\Voter;

use Sylius\Component\Core\Model\AdminUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Votes on Netgen Layouts attributes (ROLE_NGBM_*) by matching corresponding access
 * rights in Sylius eCommerce.
 */
class AdminAccessVoter implements VoterInterface
{
    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param object|null $object
     * @param array $attributes
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if ($token->getUser() instanceof AdminUserInterface) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
