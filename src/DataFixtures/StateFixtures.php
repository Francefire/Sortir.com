<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée'] as &$stateName) {
            $state = new State();
            $state->setLabel($stateName);

            $manager->persist($state);
        }

        $manager->flush();
    }
}