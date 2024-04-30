<?php

namespace App\Security\Voter;

use App\Entity\Activity;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ActivityVoter extends Voter
{
    public const CREATE = 'ACTIVITY_CREATE';
    public const EDIT = 'ACTIVITY_EDIT';
    public const VIEW = 'ACTIVITY_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::VIEW])) {
            return false;
        }

        // only vote on `Activity` objects
        if (!$subject instanceof Activity) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::VIEW:
                break;
        }

        return false;
    }

    private function canEdit(Activity $activity, User $user): bool
    {
        return $user === $activity->getHost();
    }
}
