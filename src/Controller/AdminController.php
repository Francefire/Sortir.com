<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use App\Entity\User;
use App\Form\EditUserType;
use App\Form\RegistrationFormType;
use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Campus;
use App\Form\CampusType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Firewall;

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

    #[Route('/campus', name: 'campus')]
    public function campus(CampusRepository $campusRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();

        $campusForm = $this->createForm(CampusType::class, $campus);

        $campusForm->handleRequest($request);
        if ($campusForm->isSubmitted() && $campusForm->isValid()){
            $entityManager->persist($campus);
            $entityManager->flush();
        }

        $campus = $campusRepository->findAll();
        return $this->render('admin/campus/list.html.twig', [
            "campus" => $campus,
            'campusForm' => $campusForm->createView()
        ]);

    }
    #[Route('/campus/delete/{id}', name: 'campus_delete')]
    public function campus_delete(int $id, CampusRepository $campusRepository,EntityManagerInterface $entityManager):?Response
    {
        $campus = $campusRepository->find($id);
        $entityManager->remove($campus);
        $entityManager->flush();

        return $this->redirectToRoute('app_campus');
    }

    #[Route('/city', name: 'city')]
    public function city(CityRepository $cityRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $city = new City();

        $cityForm = $this->createForm(CityType::class, $city);

        $cityForm->handleRequest($request);
        if ($cityForm->isSubmitted() && $cityForm->isValid()){
            $entityManager->persist($city);
            $entityManager->flush();
        }

        $city = $cityRepository->findAll();
        return $this->render('admin/city/list.html.twig', [
            "citys" => $city,
            'cityForm' => $cityForm->createView()
        ]);

    }
    #[Route('/city/delete/{id}', name: 'city_delete')]
    public function city_delete(int $id, CityRepository $cityRepository,EntityManagerInterface $entityManager):?Response
    {
        $city = $cityRepository->find($id);
        $entityManager->remove($city);
        $entityManager->flush();

        return $this->redirectToRoute('app_city');
    }

    #[Route('/city/edit/{id}', name: 'city_edit')]
    public function city_edit(CityRepository $cityRepository,int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $city = $cityRepository->find($id);

        $cityForm = $this->createForm(CityType::class, $city);

        $cityForm->handleRequest($request);
        if ($cityForm->isSubmitted() && $cityForm->isValid()){
            $entityManager->persist($city);
            $entityManager->flush();
            return $this->redirectToRoute('app_city');
        }


        return $this->render('admin/city/edit.html.twig', [
            "city" => $city,
            'cityForm' => $cityForm->createView()
        ]);

    }
}
