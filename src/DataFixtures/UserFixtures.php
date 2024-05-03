<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USER_REFERENCE = 'user';

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_ORGANIZER', 'ROLE_USER']);
        $admin->setPlainPassword('admin');
        $admin->setFirstname('Admin');
        $admin->setLastname('Admin');
        $admin->setEmail('admin@example.com');
        $admin->setPhone('0123456789');
        $admin->setAdministrator(true);
        $admin->setDisabled(false);
        $admin->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));

        $manager->persist($admin);
        $manager->flush();

        $organizer = new User();
        $organizer->setUsername('organizer');
        $organizer->setRoles(['ROLE_ORGANIZER', 'ROLE_USER']);
        $organizer->setPlainPassword('organizer');
        $organizer->setFirstname('Organizer');
        $organizer->setLastname('Organizer');
        $organizer->setEmail('organizer@example.com');
        $organizer->setPhone('0123456789');
        $organizer->setAdministrator(false);
        $organizer->setDisabled(false);
        $organizer->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));

        $manager->persist($organizer);
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

        $this->addReference(self::USER_REFERENCE, $organizer);
    }

    public function getDependencies()
    {
        return [
            CampusFixtures::class,
        ];
    }
}
