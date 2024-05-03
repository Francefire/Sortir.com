<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocationFixtures extends Fixture
{
    public const LOCATION_REFERENCE = 'location';
    public function load(ObjectManager $manager): void
    {
        $location = new Location();
        $location->setName('Le château des ducs de Bretagne');
        $location->setStreet('4, place Marc Elder');
        $location->setLatitude('47.216350');
        $location->setLongitude('-1.549390');
        $location->setCity($this->getReference(CityFixtures::CITY_REFERENCE));

        $manager->persist($location);
        $manager->flush();

        $this->addReference(self::LOCATION_REFERENCE, $location);
    }

    public function getDependencies()
    {
        return [
            CityFixtures::class,
        ];
    }
}
