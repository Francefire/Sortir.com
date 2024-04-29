<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\SearchFilter;
use App\Form\ActivityType;
use App\Form\LocationType;
use App\Form\SearchFilterType;
use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use App\Service\StateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/activity', name: 'activity_')]
class ActivityController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager, StateService $stateService): Response
    {
        $activity = new Activity();
        $location = new Location();
        $activityForm = $this->createForm(ActivityType::class, $activity);
        $locationForm = $this->createForm(LocationType::class, $location);


        $locationForm->handleRequest($request);
        if ($locationForm->get('add')->isClicked()) {
            if ($locationForm->isSubmitted() && $locationForm->isValid()) {
                $entityManager->persist($location);
                $entityManager->flush();
                $this->addFlash('success', 'Lieu créé avec succès');
                //TODO : Seul probleme, si l'utilisateur ajoute un lieu apres avoir rempli le formulaire d'activité, il perd les données du formulaire d'activité
            }
        }

        $activityForm->handleRequest($request);

        if ($activityForm->isSubmitted() && $activityForm->isValid()) {

            $this->addFlash('success', $stateService->setCorrectState($activity, $activityForm));

            $activity->setCampus($this->getUser()->getCampus());
            $activity->setHost($this->getUser());
            $entityManager->persist($activity);
            $entityManager->flush();
            $this->addFlash('success', 'Activité sauvegardée avec succès');

            return $this->redirectToRoute('activity_details', ['id' => $activity->getId()]);
        }

        return $this->render('activity/create.html.twig', [
            'activityForm' => $activityForm->createView(),
            'locationForm' => $locationForm->createView()
        ]);
    }

    #[Route('/cancel/{id}', name: 'cancel', methods: ['POST'])]
    public function cancel(Activity $activity, EntityManagerInterface $entityManager, StateService $stateService): Response
    {
        //TODO : Gerer exceptions dans le cas ou l'user n'est pas l'organisateur

        $activity->setState($stateService->getStates()[5]);
        $entityManager->persist($activity);
        $entityManager->flush();
        $this->addFlash('success', 'Activité annulée avec succès');
        return $this->redirectToRoute('activity_details', ['id' => $activity->getId()]);
    }

    #[Route('/publish/{id}', name: 'publish', methods: ['POST'])]
    public function publish(Activity $activity, EntityManagerInterface $entityManager, StateService $stateService): Response
    {
        //TODO : Gerer exceptions ou le cas ou l'activité est deja publié ou annulé etc etc

        $activity->setState($stateService->getStates()[1]);
        $entityManager->persist($activity);
        $entityManager->flush();
        $this->addFlash('success', 'Activité publiée avec succès');
        return $this->redirectToRoute('activity_details', ['id' => $activity->getId()]);
    }

    #[Route('/list', name: 'list')]
    public function list(ActivityRepository $activityRepository, Request $request, UserRepository $userRepository): Response
    {
        $searchFilter = new SearchFilter();
        $searchFilterForm = $this->createForm(SearchFilterType::class, $searchFilter);

        $searchFilterForm->handleRequest($request);

        if ($searchFilterForm->isSubmitted() && $searchFilterForm->isValid()) {
            $user = $userRepository->find($this->getUser());
            $activities = $activityRepository->findActivitiesBySearchFilter($searchFilter, $user);
            if (!$searchFilter->getFinished()) {
                $activities = array_merge($activityRepository->findCreatedNotPublishedActivities($user), $activities);
            }

        } else {
            $user = $userRepository->find($this->getUser());
            $defaultSearch = new SearchFilter();
            $activities = $activityRepository->findActivitiesBySearchFilter($defaultSearch, $user);
            if (!$searchFilter->getFinished()) {
                $activities = array_merge($activityRepository->findCreatedNotPublishedActivities($user), $activities);
            }
        }

        return $this->render('activity/list.html.twig', [
            'searchFilter' => $searchFilter,
            'searchFilterForm' => $searchFilterForm->createView(),
            'activities' => $activities
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Activity $activity, EntityManagerInterface $entityManager, StateService $stateService, Request $request): Response
    {
        $editActivityForm = $this->createForm(ActivityType::class, $activity);

        $editActivityForm->handleRequest($request);

        if ($editActivityForm->isSubmitted() && $editActivityForm->isValid()) {

            $this->addFlash('info', $stateService->setCorrectState($activity, $editActivityForm));
            $entityManager->persist($activity);
            $entityManager->flush();
            $this->addFlash('success', 'Activité modifiée avec succès');
        }

        return $this->render('activity/edit.html.twig', [
            'activity' => $activity,
            'editActivityForm' => $editActivityForm->createView()
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function details(Activity $activity): Response
    {
        return $this->render('activity/details.html.twig', [
            'activity' => $activity,
            'user' => $this->getUser()
        ]);
    }

    #[Route('/join/{id}', name: 'join', methods: ['POST'])]
    public function entry(Activity $activity, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        //TODO : Utiliser un service pour gérer les inscriptions
        if ($activity->getParticipants()->contains($user)) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cette sortie');
        } else if ($activity->getMaxParticipants() <= count($activity->getParticipants())) {
            $this->addFlash('warning', 'La sortie est complète');
        } else if ($activity->getState()->getId() != 2) {
            switch ($activity->getState()->getId()) {
                case 1:
                    $this->addFlash('warning', 'La sortie n\'est pas encore ouverte');
                    break;
                case 3:
                    $this->addFlash('warning', 'La sortie est clôturée');
                    break;
                case 4:
                    $this->addFlash('warning', 'La sortie à deja commencée');
                    break;
                case 5:
                    $this->addFlash('warning', 'La sortie est terminée');
                    break;
                case 6:
                    $this->addFlash('warning', 'La sortie est annulée');
                    break;
            }
        } else {
            $this->addFlash('success', 'Vous êtes inscrit à la sortie');
            $activity->addParticipant($user);
            $entityManager->persist($activity);
            $entityManager->flush();
        }
        return $this->redirectToRoute('activity_details', ['id' => $activity->getId()]);
    }

    #[Route('/leave/{id}', name: 'leave', methods: ['POST'])]
    public function leave(Activity $activity, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($activity->getParticipants()->contains($user)) {
            $this->addFlash('success', 'Vous avez annulé votre participation à la sortie');
            $activity->removeParticipant($user);
            $entityManager->persist($activity);
            $entityManager->flush();
        } else {
            $this->addFlash('warning', 'Vous n\'êtes pas inscrit à cette sortie');
        }
        return $this->redirectToRoute('activity_details', ['id' => $activity->getId()]);
    }

}