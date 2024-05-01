<?php

namespace App\EventListener;

use App\Entity\Activity;
use App\Service\ActivityService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postLoad, method: 'onPostLoad', entity: Activity::class)]
final class ActivityListener
{
    public function __construct(
        private readonly ActivityService $activityService,
    )
    {
    }

    public function onPostLoad(Activity $activity): void
    {
        $this->activityService->updateActivity($activity);
    }
}
