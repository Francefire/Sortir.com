<?php

namespace App\EventListener;

use App\Entity\Activity;
use App\Service\StateService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preFlush, method: 'verifyState' ,entity: Activity::class)]
class ActivityFlushListener
{
    public function verifyState(Activity $activity, $args): void
    {
        $stateService = new StateService($args->getObjectManager());
        if($activity->getState()->getId() != 1)
        {
            $stateService->correctActivityState($activity);
        }
    }
}