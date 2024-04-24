<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\RegistrationFormType;
use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/users', name: 'admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users
        ]);
    }

    #[Route('/users/create', name: 'admin_users_create')]
    public function users_create(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $user = new User();
        $user->setAdministrator(false);
        $user->setActive(false);
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $registrationForm->get('plainPassword')->getData()
                )
            );
            $user->setCampus($campusRepository->find(1)); // Default campus for admin //TODO: DELETE THIS LINE
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Compte crée avec succès');
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/users_create.html.twig', [
            'controller_name' => 'AdminController',
            'registrationForm' => $registrationForm,
        ]);
    }

    #[Route('/users/edit/{id}', name: 'admin_users_edit')]
    public function users_edit(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, int $id): Response
    {
        $user = $userRepository->find($id);

        $editForm = $this->createForm(EditUserType::class, $user);

        $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()) {
            if (!empty($user->getPlainPassword())) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
            }
            $entityManager->flush();
            $this->addFlash('success', 'Profil modifié avec succès');
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/users_edit.html.twig', [
            'controller_name' => 'AdminController',
            'editForm' => $editForm->createView(),
            'user' => $user
        ]);
    }

    #[Route('/users/delete/{id}', name: 'admin_users_delete')]
    public function users_delete(EntityManagerInterface $em, UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Compte supprimé avec succès');
        return $this->redirectToRoute('app_admin_users');
    }
}
