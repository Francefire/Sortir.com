<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\MailerService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::prePersist, method: 'onPrePersist', entity: User::class)]
#[AsEntityListener(event: Events::postPersist, method: 'onPostPersist', entity: User::class)]
final class UserListener
{

    private ?string $plainPassword;

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly MailerService               $mailerService
    )
    {
    }

    public function onPrePersist(User $user): void
    {
        $this->plainPassword = $user->getPlainPassword();

        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
    }

    public function onPostPersist(User $user): void
    {
        $this->mailerService->sendAccountCreated($user->getEmail(), $user->getUsername(), $this->plainPassword);
    }
}
