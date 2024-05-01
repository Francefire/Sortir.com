<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\SearchFilter;
use App\Form\ActivityType;
use App\Form\EditActivityType;
use App\Form\LocationType;
use App\Repository\ActivityRepository;
use App\Service\ActivityService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FiltersFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/activities', name: 'activities_')]
class ActivityController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function activities(ActivityRepository $activityRepository): Response
    {
        $filters = new SearchFilter();
        $filtersForm = $this->createForm(FiltersFormType::class, $filters);

        $activities = $activityRepository->findAll();

        return $this->render('activities/list.html.twig', [
            'filtersForm' => $filtersForm->createView(),
            'activities' => $activities,
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, ActivityService $activityService, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $location = new Location();
        $activity = new Activity();

        $locationForm = $this->createForm(LocationType::class, $location);
        $createForm = $this->createForm(ActivityType::class, $activity);

        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Location created!');
            return $this->render('activities/create.html.twig', [
                'locationForm' => $locationForm->createView(),
                'createForm' => $createForm->createView(),
            ]);
        }

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $stateId = $createForm->get('save')->isClicked() ? 0 : 1;

            $activityService->createActivity($activity, $user, $stateId);

            $this->addFlash('success', 'Activity created!');
            return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
        }

        return $this->render('activities/create.html.twig', [
            'locationForm' => $locationForm->createView(),
            'createForm' => $createForm->createView(),
        ]);
    }

    #[Route('/{id}', name: 'details', methods: ['GET'])]
    public function details(Activity $activity): Response
    {
        return $this->render('activities/details.html.twig', [
            'activity' => $activity,
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activity $activity, ActivityService $activityService): Response
    {
        $editForm = $this->createForm(EditActivityType::class, $activity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $stateId = $editForm->get('save')->isClicked() ? 0 : 1;

            $activityService->editActivity($activity, $stateId);

            $this->addFlash('success', 'Activity edited!');
            return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
        }

        return $this->render('activities/edit.html.twig', [
            'editForm' => $editForm->createView(),
        ]);
    }

    #[Route('/{id}/join', name: 'join', methods: ['POST'])]
    public function join(Activity $activity, ActivityService $activityService): Response
    {
        $user = $this->getUser();

        $activityService->joinActivity($activity, $user);

        $this->addFlash('success', 'Activity joined!');
        return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
    }

    #[Route('/{id}/leave', name: 'leave', methods: ['POST'])]
    public function leave(Activity $activity, ActivityService $activityService): Response
    {
        $user = $this->getUser();

        $activityService->leaveActivity($activity, $user);

        $this->addFlash('success', 'Activity left!');
        return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
    }
}