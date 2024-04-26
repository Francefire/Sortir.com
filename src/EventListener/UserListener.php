<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: User::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: User::class)]
class UserListener
{
    public function postPersist(User $user): void
    {
        // TODO: Envoyer un mail à l'utilisateur pour lui dire que son compte à été crée
    }

    public function postUpdate(User $user): void
    {
        // TODO: Sauvegarder la photo de profile dans le dossier public
    }
}