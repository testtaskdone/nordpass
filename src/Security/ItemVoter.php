<?php

namespace App\Security;

use App\Entity\Item;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ItemVoter extends Voter
{
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';

    protected function supports(string $attribute, $subject)
    {
        return $subject instanceof Item
            && (
                $attribute === self::ACTION_UPDATE
                || $attribute === self::ACTION_DELETE
            );
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /** @var Item $subject */
        $user = $subject->getUser();

        switch ($attribute) {
            case self::ACTION_UPDATE;
            case self::ACTION_DELETE:
                return $user !== null && $user->getId() === $token->getUser()->getId();
            break;
        }

        return false;
    }
}
