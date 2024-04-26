<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\SearchFilter;
use App\Form\ActivityType;
use App\Form\LocationType;
use App\Form\SearchFilterType;
use App\Repository\ActivityRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/activity', name: 'activity_')]
class ActivityController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager, StateRepository $stateRepository): Response
    {
        $states = $stateRepository->findAll();
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
            if ($activityForm->get('save')->isClicked()) {
                $activity->setState($states[0]);
                $this->addFlash('info', 'Activité enregistrée en brouillon');
            } elseif ($activityForm->get('publish')->isClicked()) {
                $activity->setState($states[1]);
                $this->addFlash('info', 'Activité publiée');
            }
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

    #[Route('/list', name: 'list')]
    public function list(ActivityRepository $activityRepository, Request $request, UserRepository $userRepository): Response
    {
        $searchFilter = new SearchFilter();
        $searchFilterForm = $this->createForm(SearchFilterType::class, $searchFilter);

        $searchFilterForm->handleRequest($request);

        if ($searchFilterForm->isSubmitted() && $searchFilterForm->isValid()) {
            $user = $userRepository->find($this->getUser());
            $activities = $activityRepository->findActivitiesBySearchFilter($searchFilter, $user);

        } else {
            $activities = $activityRepository->findPublishedActivity();
        }

        return $this->render('activity/list.html.twig', [
            'searchFilter' => $searchFilter,
            'searchFilterForm' => $searchFilterForm->createView(),
            'activities' => $activities
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Activity $activity, EntityManagerInterface $entityManager, ActivityRepository $activityRepository, StateRepository $stateRepository, Request $request): Response
    {
        $states = $stateRepository->findAll();
        dump($activity);

        $editActivityForm = $this->createForm(ActivityType::class, $activity);

        $editActivityForm->handleRequest($request);

        if ($editActivityForm->isSubmitted() && $editActivityForm->isValid()) {

            if ($editActivityForm->get('save')->isClicked()) {
                $activity->setState($states[0]);
                $this->addFlash('info', 'Activité enregistrée en brouillon');
            } elseif ($editActivityForm->get('publish')->isClicked()) {
                $activity->setState($states[1]);
                $this->addFlash('info', 'Activité publiée');
            }
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