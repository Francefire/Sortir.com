<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;

use App\Entity\State;
use App\Repository\ActivityRepository;
use App\Repository\StateRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activity')]
class ActivityController extends AbstractController
{
    #[Route('/create', name: 'activity_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, StateRepository $stateRepository) : Response
    {
        $states = $stateRepository->findAll();
        $activity = new Activity();
        $activityForm = $this->createForm(ActivityType::class, $activity);


        $activityForm->handleRequest($request);


        if ($activityForm->isSubmitted() && $activityForm->isValid()) {
            if ($activityForm->get('save')->isClicked()) {
                $activity->setState($states[0]);
            } elseif ($activityForm->get('publish')->isClicked()) {
                $activity->setState($states[1]);
            }
            $activity->setCampus($this->getUser()->getCampus());
            $activity->setHost($this->getUser());
            $entityManager->persist($activity);
            $entityManager->flush();
        }

        return $this->render('activity/create.html.twig',[
            'activityForm' => $activityForm->createView()
        ]);
    }

    #[Route('/list', name: 'activity_list')]
    public function list(): Response
    {
        return $this->render('activity/list.html.twig');
    }

    #[Route('/edit/{id}', name: 'activity_edit')]
    public function edit(Activity $activity, EntityManagerInterface $entityManager, ActivityRepository $activityRepository): Response
    {

        return $this->render('activity/edit.html.twig');
    }
}