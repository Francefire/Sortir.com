<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activity', name: 'activity_')]
class ActivityController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $activity = new Activity();
        $activityForm = $this->createForm(ActivityType::class, $activity);

        $activityForm->handleRequest($request);

        if ($activityForm->isSubmitted() && $activityForm->isValid()) {
            if ($activityForm->getClickedButton() && 'save' === $activityForm->getClickedButton()->getName()) {
                $activity->setState('Créée');
                $entityManager->persist($activity);
                $entityManager->flush();
            } elseif ($activityForm->getClickedButton() && 'publish' === $activityForm->getClickedButton()->getName()) {
                $activity->setState('Ouverte');
                $entityManager->persist($activity);
                $entityManager->flush();
            } elseif($activityForm->getClickedButton() && 'cancel' === $activityForm->getClickedButton()->getName()) {

            }

        }

        return $this->render('activity/create.html.twig',[
            'activityForm' => $activityForm->createView()
        ]);
    }

}