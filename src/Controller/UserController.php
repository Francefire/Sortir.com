<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/profile/{id}', name: 'user_profile')]
    public function profile(UserRepository $userRepository, User $user, int $id): Response
    {
        dump($user);
        return $this->render('user/profile.html.twig', compact('user', 'id'));
    }

    #[Route('/user/edit/{id}', name: 'user_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, User $user): Response
    {
        $editForm = $this->createForm(EditUserType::class, $user);

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if (!empty($user->getPlainPassword())) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
            }

            $entityManager->flush();
            $this->addFlash('success', 'Profil modifié avec succès');
            return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'editForm' => $editForm->createView(),
            'user' => $user
        ]);
    }


    #[Route('/user/activity/{id}', name: 'user_activity')]
    public function activity(User $user, ActivityRepository $activityRepository): Response
    {
        $userActivity = $activityRepository->findActivityByUser($user);
        dump($userActivity);
        return $this->render('user/activity.html.twig', compact('userActivity'));
    }
}
