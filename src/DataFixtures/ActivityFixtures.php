<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActivityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $activity = new Activity();
        $activity->setName('');
        $activity->setDescription();
        $activity->setRegisterLimitDatetime();
        $activity->setStartDatetime();
        $activity->setDuration();
        $activity->setLocation();
        $activity->setMaxParticipants();
        $activity->setHost($this->getReference(UserFixtures::USER_REFERENCE));
        $activity->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));
    }

    public function getDependencies()
    {
        return [
            CampusFixtures::class,
            UserFixtures::class
        ];
    }
}
