<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\User;
use App\Repository\StateRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;

class ActivityService
{
    private int $oneMonth = 2629743;
    private readonly array $states;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StateRepository        $stateRepository
    )
    {
        $this->states = $this->stateRepository->findAll();
    }

    public function createActivity(Activity $activity, User $user, int $stateId): void
    {
        $activity->setHost($user);
        $activity->setCampus($user->getCampus());
        $activity->setState($this->states[$stateId]);

        $this->entityManager->persist($activity);
        $this->entityManager->flush();
    }

    public function editActivity(Activity $activity, int $stateId): void
    {
        if ($this->isEditable($activity) && $stateId == 1) {
            $activity->setState($this->states[1]);
        }

        $this->entityManager->flush();
    }

    private function isEditable(Activity $activity): bool
    {
        return $activity->getState()->getLabel() == 'Créée';
    }

    public function joinActivity(Activity $activity, User $user): void
    {
        if (!($this->canInteract($activity, $user))) {
            return;
        }

        if ($activity->getParticipants()->contains($user)) {
            return;
        }

        $activity->addParticipant($user);
        $this->entityManager->flush();
    }

    private function canInteract(Activity $activity, $user): bool
    {
        if (!($this->isEditable($activity))) {
            return false;
        }

        if ($activity->getHost() === $user) {
            return false;
        }

        return true;
    }

    public function leaveActivity(Activity $activity, User $user): void
    {
        if (!($this->canInteract($activity, $user))) {
            return;
        }

        if (!($activity->getParticipants()->contains($user))) {
            return;
        }

        $activity->removeParticipant($user);
        $this->entityManager->flush();
    }

    public function updateActivity(Activity $activity): void
    {
        if ($activity->getState()->getLabel() == ['Créée', 'Annulée']) {
            return;
        }

        $datetimeNow = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datetimeNow = $datetimeNow->getTimestamp();

        $activityRegisterLimitDatetime = $activity->getRegisterLimitDatetime()->getTimestamp();
        $activityStartDatetime = $activity->getStartDatetime()->getTimestamp();
        $activityDuration = $activity->getDuration()->getTimestamp();
        $activityEndDatetime = $activityStartDatetime + $activityDuration;

        switch (true) {
            case ($activityRegisterLimitDatetime > $datetimeNow):
                $activity->setState($this->states[1]);
                break;
            case ($activityRegisterLimitDatetime < $datetimeNow):
                if ($activityStartDatetime > $datetimeNow) {
                    $activity->setState($this->states[2]);
                    break;
                }

                if ($activityStartDatetime < $datetimeNow) {
                    $activity->setState($this->states[2]);
                    break;
                }

                $activity->setState($this->states[4]);
                break;
            case (($activityEndDatetime + $this->oneMonth) < $datetimeNow):
                $this->deleteActivity($activity);
                return;
            default:
                $activity->setState($this->states[0]);
        }

        $this->entityManager->flush();
    }

    private function deleteActivity(Activity $activity): void
    {

    }
}