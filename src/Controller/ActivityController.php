<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\SearchFilter;
use App\Form\ActivityType;
use App\Form\SearchFilterType;
use App\Repository\ActivityRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activity', name: 'activity_')]
class ActivityController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager, StateRepository $stateRepository): Response
    {
        $states = $stateRepository->findAll();
        $activity = new Activity();
        $activityForm = $this->createForm(ActivityType::class, $activity);


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

            //TODO: Rediriger vers la page de detail de la sortie
        }

        return $this->render('activity/create.html.twig', [
            'activityForm' => $activityForm->createView()
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(ActivityRepository $activityRepository, Request $request, UserRepository $userRepository): Response
    {
        $searchFilter = new SearchFilter();
        $searchFilterForm = $this->createForm(SearchFilterType::class, $searchFilter);

        $searchFilterForm->handleRequest($request);

        if($searchFilterForm->isSubmitted() && $searchFilterForm->isValid()){
            $user = $userRepository->find($this->getUser());
            $activities = $activityRepository->findActivitiesBySearchFilter($searchFilter,$user);

        }else{
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
}