<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Tasks;
use App\Form\CampusType;
use App\Form\TaskType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/create_user', name: 'create_user')]
    public function create_user(): Response
    {

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
}
