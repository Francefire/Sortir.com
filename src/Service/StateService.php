<?php

namespace App\Service;

use App\Entity\State;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;

class StateService
{
    private EntityManagerInterface $em;
    private array $states;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    private function getRepository(): StateRepository
    {
        return $this->em->getRepository(State::class);
    }

    public function getStates(): array
    {
        if (empty($this->states)) {
            $this->states = $this->getRepository()->findAll();
        }
        return $this->states;
    }

    public function correctActivityState($activity): void
    {
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone('Europe/Paris'));
        $states = $this->getStates();

        if ($activity->getRegisterLimitDatetime() < $now) {
            $activity->setState($states[2]);
        }
        if ($activity->getStartDatetime() < $now) {
            $activity->setState($states[3]);
        }
        $activityEndTime = $activity->getStartDatetime()->getTimestamp() + $activity->getDuration()->getTimestamp();
        if ($activityEndTime < $now->getTimestamp()) {
            $activity->setState($states[4]);
        }

    }

    /**
     * @param $activity
     * @param $activityForm
     * @return string Success flash message to display
     */
    public function setCorrectState($activity, $activityForm): string
    {
        $flashMessage = '';
        $states = $this->getStates();
        if ($activityForm->get('save')->isClicked()) {
            $activity->setState($states[0]);
            $flashMessage = 'Activité enregistrée';
        } elseif ($activityForm->get('publish')->isClicked()) {
            $this->correctActivityState($activity);
            $flashMessage = 'Activité publiée';
        }
        return $flashMessage;
    }


}