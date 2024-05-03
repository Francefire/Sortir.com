<?php

namespace App\Scheduler\Handler;

use App\Entity\Activity;
use App\Entity\State;
use App\Scheduler\Message\CheckState;
use App\Service\ActivityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckStateHandler
{
    public function __construct(private EntityManagerInterface $em)
    {

    }
    public function __invoke(CheckState $message)
    {
        $activityService = new ActivityService($this->em, $this->em->getRepository(State::class));
        $activitesToCheck = $this->em->getRepository(Activity::class)->findPresentActivites();
        foreach($activitesToCheck as $activity)
        {
            $activityService->updateActivity($activity);
            $this->em->flush();
        }

    }

}