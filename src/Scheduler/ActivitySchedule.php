<?php

namespace App\Scheduler;

use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
final class ActivitySchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache
    ) {
    }


    public function getSchedule(): Schedule
    {
        // @TODO - A Modifier, une fois par heure
        return (new Schedule())
            ->add(
                RecurringMessage::every(
                    '1 minute',
                    new Message\CheckState(),
                    from: 'now'
                )
            )
            ->stateful($this->cache)
        ;
    }
}
