<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: User::class)]
class UserListener
{
    public function postPersist(User $user, LifecycleEventArgs $args): void
    {
        $user = $args->getObject();

        if (!$user instanceof User) {
            return;
        }
        
        // TODO: Envoyer un mail à l'utilisateur pour lui dire que son compte à été crée
    }
}