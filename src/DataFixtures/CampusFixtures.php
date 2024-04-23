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
        $campusReference = new Campus();
        $campusReference->setName('Saint-Herblain');

        $manager->persist($campusReference);

        foreach (['La Roche-sur-Yon', 'Chartres-de-Bretagne', 'Quimper', 'Niort', 'Angers', 'Saint-Nazaire'] as &$campusName) {
            $campus = new Campus();
            $campus->setName($campusName);

            $manager->persist($campus);
        }

        $manager->flush();

        $this->addReference(self::CAMPUS_REFERENCE, $campusReference);
    }
}
