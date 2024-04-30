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
        $city->setName('Ville imaginaire');
        $city->setPostalCode('99999');

        $manager->persist($city);

        $this->addReference(self::CITY_REFERENCE, $city);

        /*
         * TODO: A tester
         *
        if (($handle = fopen('data/villes.csv', "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                if ($data[0] === 'zip_code') {
                    continue;
                }

                $city = new City();
                $city->setPostalCode($data[0]);
                $city->setName(ucwords($data[1]));

                $manager->persist($city);
            }

            fclose($handle);
        }
        */

        $manager->flush();
    }
}
