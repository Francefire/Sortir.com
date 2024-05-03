<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\ActivityRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/users', name: 'users_')]
class UserController extends AbstractController
{
    #[Route('/{id}', name: 'profile', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function profile(User $user): Response
    {
        return $this->render('users/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[IsGranted('USER_EDIT', 'user')]
    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, UserService $userService, User $user): Response
    {
        $editForm = $this->createForm(EditUserType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $editForm->get('avatar')->getData();

            $userService->editUser($user, $file);

            $this->addFlash('success', 'Profil modifié avec succès');
            return $this->redirectToRoute('users_profile', ['id' => $user->getId()]);
        }

        return $this->render('users/edit.html.twig', [
            'editForm' => $editForm->createView(),
            'user' => $user
        ]);
    }


    #[Route('/{id}/activities', name: 'activities', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function activity(User $user, ActivityRepository $activityRepository): Response
    {
        $userActivities = $activityRepository->findActivitiesByUser($user);

        return $this->render('users/activities.html.twig', [
            'userActivities' => $userActivities,
        ]);
    }
}
