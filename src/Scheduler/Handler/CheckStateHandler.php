<?php

namespace App\Scheduler\Handler;

use App\Entity\Activity;
use App\Scheduler\Message\CheckState;
use App\Service\StateService;
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
        // @TODO - Implement the logic to check the state of the activity
        $stateService = new StateService($this->em);
        $activitesToCheck = $this->em->getRepository(Activity::class)->findPresentActivites();
        foreach($activitesToCheck as $activity)
        {
            /*
            $stateService->correctActivityState($activity);
            $this->em->persist($activity);
            */
            //dump($this->em);
            $this->em->flush();
        }

    }

}