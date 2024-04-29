<?php

namespace App\EventSubscriber;

use App\Service\FileService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormEvents;

class UserEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onFormPostSubmit',
        ];
    }

    public function onFormPostSubmit(PostSubmitEvent $event): void
    {
        $user = $event->getData();
        $form = $event->getForm();

        $file = $form->get('profilePicture')->getData();

        if ($file) {
            $fileService = new FileService();
            $fileName = $fileService->upload($file);
            $user->setProfilePictureFilename($fileName);
        }
    }
}
