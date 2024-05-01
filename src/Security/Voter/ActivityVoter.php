<?php

namespace App\Security\Voter;

use App\Entity\Activity;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ActivityVoter extends Voter
{
    public const EDIT = 'ACTIVITY_EDIT';
    public const JOIN = 'ACTIVITY_JOIN';
    public const LEAVE = 'ACTIVITY_LEAVE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT, self::JOIN, self::LEAVE])) {
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
        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::JOIN => $this->canJoin($subject, $user),
            self::LEAVE => $this->canLeave($subject, $user),
            default => false,
        };

    }

    private function canEdit(Activity $activity, User $user): bool
    {
        return $activity->getHost()->getId() === $user->getId();
    }

    private function canJoin(Activity $activity, User $user): bool
    {
        if ($activity->getHost()->getId() === $user->getId()) {
            return false;
        }

        return !$activity->getParticipants()->contains($user);
    }

    private function canLeave(Activity $activity, User $user): bool
    {
        if ($activity->getHost()->getId() === $user->getId()) {
            return false;
        }

        return $activity->getParticipants()->contains($user);
    }
}
