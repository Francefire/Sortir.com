<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setRoles(['ROLE_ADMIN', 'ROLE_ORGANIZER', 'ROLE_USER']);
        $adminUser->setPlainPassword('admin');
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
        $user->setPlainPassword('organizer');
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
        $user->setPlainPassword('user');
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
