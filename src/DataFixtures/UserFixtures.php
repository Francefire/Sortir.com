<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword('$2y$13$BLOHm3ry89axh4hP8IRkZ.TtLvFiOD7ylUe33eEfD0fjS1twlJi8u'); // admin
        $adminUser->setFirstname('Admin');
        $adminUser->setLastname('Admin');
        $adminUser->setEmail('admin@example.com');
        $adminUser->setPhone('0123456789');
        $adminUser->setAdministrator(true);
        $adminUser->setActive(true);
        $adminUser->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));

        $manager->persist($adminUser);
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
        $user->setActive(true);
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
