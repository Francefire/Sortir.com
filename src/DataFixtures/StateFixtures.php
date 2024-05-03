<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture
{

    public const STATE_REFERENCE = 'state';

    public function load(ObjectManager $manager): void
    {
        $stateReference = new State();
        $stateReference->setLabel('Créée');

        $manager->persist($stateReference);

        foreach (['Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée'] as &$stateName) {
            $state = new State();
            $state->setLabel($stateName);

            $manager->persist($state);
        }

        $manager->flush();

        $this->addReference(self::STATE_REFERENCE, $stateReference);
    }
}