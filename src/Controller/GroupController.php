<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/groups', name: 'groups_')]
class GroupController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(GroupRepository $groupRepository): Response
    {
        $user = $this->getUser();

        $groups = $groupRepository->findGroupByUser($user);

        return $this->render('groups/list.html.twig', [
            'groups' => $groups,
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $group = new Group();

        $createForm = $this->createForm(GroupType::class, $group);

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $group->setLeader($user);

            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('success', 'Groupe crée');
            return $this->redirectToRoute('groups_details', ['id' => $group->getId()]);
        }

        return $this->render('groups/create.html.twig', [
            'createForm' => $createForm->createView(),
        ]);
    }

    #[Route('/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function details(Group $group): Response
    {
        return $this->render('groups/details.html.twig', [
            'group' => $group,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Group $group): Response
    {
        $editForm = $this->createForm(GroupType::class, $group);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Groupe modifié');
            return $this->redirectToRoute('groups_details', ['id' => $group->getId()]);
        }

        return $this->render('groups/edit.html.twig', [
            'editForm' => $editForm->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(EntityManagerInterface $entityManager, Group $group): Response
    {
        $entityManager->remove($group);
        $entityManager->flush();

        $this->addFlash('success', 'Groupe supprimé');
        return $this->redirectToRoute('groups_list');
    }
}
