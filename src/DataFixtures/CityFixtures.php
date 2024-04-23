<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public const CITY_REFERENCE = 'city';

    public function load(ObjectManager $manager): void
    {
        $city = new City();
        $city->setName('Nantes');
        $city->setPostalCode('44000');

        $manager->persist($city);

        $manager->flush();

        $this->addReference(self::CITY_REFERENCE, $city);
    }
}
