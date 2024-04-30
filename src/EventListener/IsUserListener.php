<?php

namespace App\EventListener;

use App\Controller\ActivityController;
use App\Controller\UserController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class IsUserListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[AsEventListener(event: KernelEvents::CONTROLLER)]
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof UserController) {
            if ($controller[1] == "edit") {
                $id = $event->getRequest()->attributes->get('id');
                $userID = $this->security->getUser()->getId();

                if ($userID == $id) {
                    return;
                }

                $response = new RedirectResponse('/user/profile/' . $id);
                $response->send();
            }
        }

        if ($controller[0] instanceof ActivityController) {
            if ($controller[1] == "edit") {
                // TODO: Get activity host id

                /*$id = $event->getRequest()->attributes->get('id');

                $response = new RedirectResponse('/activity/details/' . $id);
                $response->send();*/
            }
        }
    }
}
