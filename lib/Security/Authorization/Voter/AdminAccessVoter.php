<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Security\Authorization\Voter;

use Sylius\Component\Core\Model\AdminUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function str_starts_with;

/**
 * Votes on Netgen Layouts attributes (ROLE_NGLAYOUTS_*) by matching corresponding access
 * rights in Sylius eCommerce.
 *
 * @extends \Symfony\Component\Security\Core\Authorization\Voter\Voter<string, \Netgen\Layouts\API\Values\Value|null>
 */
final class AdminAccessVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return str_starts_with($attribute, 'nglayouts:');
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return $token->getUser() instanceof AdminUserInterface;
    }
}
