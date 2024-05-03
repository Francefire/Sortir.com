<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\SearchFilter;
use App\Form\ActivityType;
use App\Form\EditActivityType;
use App\Form\LocationType;
use App\Form\SearchFilterType;
use App\Repository\ActivityRepository;
use App\Service\ActivityService;
use App\Service\DetectDevice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/activities', name: 'activities_')]
class ActivityController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET', 'POST'])]
    public function activities(Request $request, DetectDevice $detectDevice, ActivityRepository $activityRepository): Response
    {
        $user = $this->getUser();

        $searchFilter = new SearchFilter();
        $filterForm = $this->createForm(SearchFilterType::class, $searchFilter);

        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $activities = $activityRepository->findActivitiesBySearchFilter($searchFilter, $user);
        } else {
            $activities = $activityRepository->findAll();
        }

        return $this->render('activities/list.html.twig', [
            'filterForm' => $filterForm->createView(),
            'activities' => $activities,
            'isMobile' => $detectDevice->isMobile($request->headers->get('User-Agent'))
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, ActivityService $activityService, DetectDevice $detectDevice, EntityManagerInterface $entityManager): Response
    {
        $ua = $request->headers->get('User-Agent');

        if ($detectDevice->isMobile($ua)) {
            $this->addFlash('error', 'Vous ne pouvez pas créer d\'activités sur téléphone');
            return $this->redirectToRoute('activities_list');
        }

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

            $file = $createForm->get('image')->getData();

            $activityService->createActivity($activity, $user, $file, $stateId);

            $this->addFlash('success', 'Activity created!');
            return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
        }

        return $this->render('activities/create.html.twig', [
            'locationForm' => $locationForm->createView(),
            'createForm' => $createForm->createView(),
        ]);
    }

    #[Route('/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function details(Activity $activity): Response
    {
        return $this->render('activities/details.html.twig', [
            'activity' => $activity,
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Activity $activity, ActivityService $activityService): Response
    {
        $editForm = $this->createForm(EditActivityType::class, $activity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $stateId = $editForm->get('save')->isClicked() ? 0 : 1;

            $file = $editForm->get('image')->getData();

            $activityService->editActivity($activity, $stateId, $file);

            $this->addFlash('success', 'Activity edited!');
            return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
        }

        return $this->render('activities/edit.html.twig', [
            'editForm' => $editForm->createView(),
        ]);
    }

    #[Route('/{id}/join', name: 'join', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function join(Activity $activity, ActivityService $activityService): Response
    {
        $user = $this->getUser();

        $activityService->joinActivity($activity, $user);

        $this->addFlash('success', 'Activity joined!');
        return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
    }

    #[Route('/{id}/leave', name: 'leave', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function leave(Activity $activity, ActivityService $activityService): Response
    {
        $user = $this->getUser();

        $activityService->leaveActivity($activity, $user);

        $this->addFlash('success', 'Activity left!');
        return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
    }

    #[Route('/{id}/cancel', name: 'cancel', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function cancel(Activity $activity, EntityManagerInterface $entityManager, ActivityService $activityService): Response
    {
        //TODO : Gerer exceptions dans le cas ou l'user n'est pas l'organisateur

        $activity->setState($activityService->getStates()[5]);
        $entityManager->flush();
        $this->addFlash('success', 'Activité annulée avec succès');
        return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
    }

    #[Route('/{id}/publish', name: 'publish', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function publish(Activity $activity, EntityManagerInterface $entityManager, ActivityService $activityService): Response
    {
        //TODO : Gerer exceptions ou le cas ou l'activité est deja publié ou annulé etc etc

        $activity->setState($activityService->getStates()[5]);
        $entityManager->flush();
        $this->addFlash('success', 'Activité publiée avec succès');
        return $this->redirectToRoute('activities_details', ['id' => $activity->getId()]);
    }
}