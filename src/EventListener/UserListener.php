<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\MailerService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'onPostPersist', entity: User::class)]
class UserListener
{
    public function __construct(
        private readonly MailerService $mailerService
    )
    {
    }

    public function onPostPersist(User $user): void
    {
        $this->mailerService->sendAccountCreated($user->getEmail(), $user->getUsername(), $user->getPassword());
    }
}