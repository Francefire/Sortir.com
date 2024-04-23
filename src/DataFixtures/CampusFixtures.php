<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public const CAMPUS_REFERENCE = 'campus';

    public function load(ObjectManager $manager): void
    {
        $campus = new Campus();
        $campus->setName("Saint-Herblains");

        $manager->persist($campus);
        $manager->flush();

        $this->addReference(self::CAMPUS_REFERENCE, $campus);
    }
}
