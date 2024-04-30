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
        // @TODO - A Modifier, il faudra utiliser ca pour archiver les sorties, mais pas les etats, ou verifier les etats moins souvent
        return (new Schedule())
            ->add(
                RecurringMessage::every(
                    '1 minute',
                    new Message\CheckState(),
                    from: new \DateTimeImmutable('13:00', new \DateTimeZone('Europe/Paris'))
                )
            )
            ->stateful($this->cache)
        ;
    }
}
