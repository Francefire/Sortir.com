<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setRoles(['ROLE_ADMIN', 'ROLE_ORGANIZER', 'ROLE_USER']);
        $adminUser->setPassword('$2y$13$BLOHm3ry89axh4hP8IRkZ.TtLvFiOD7ylUe33eEfD0fjS1twlJi8u'); // admin
        $adminUser->setFirstname('Admin');
        $adminUser->setLastname('Admin');
        $adminUser->setEmail('admin@example.com');
        $adminUser->setPhone('0123456789');
        $adminUser->setAdministrator(true);
        $adminUser->setDisabled(false);
        $adminUser->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));

        $manager->persist($adminUser);
        $manager->flush();

        $user = new User();
        $user->setUsername('organizer');
        $user->setRoles(['ROLE_ORGANIZER', 'ROLE_USER']);
        $user->setPassword('$2y$13$S90ikw6boIj/vv1wXR8CDeXDwylu/g34hK368CvANvYCSwHqssFEO'); // organizer
        $user->setFirstname('Organizer');
        $user->setLastname('Organizer');
        $user->setEmail('organizer@example.com');
        $user->setPhone('0123456789');
        $user->setAdministrator(false);
        $user->setDisabled(false);
        $user->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setUsername('user');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('$2y$13$higOH5e6Iq7cdjvkd5f5d.nriAYzNnSJzq24rYNFPHzSbfbxwWWJG'); // user
        $user->setFirstname('User');
        $user->setLastname('User');
        $user->setEmail('user@example.com');
        $user->setPhone('0123456789');
        $user->setAdministrator(false);
        $user->setDisabled(false);
        $user->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CampusFixtures::class,
        ];
    }
}
