<?php

namespace App\Service;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;

class ActivityService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function getEm() : EntityManagerInterface
    {
        return $this->em;
    }

    private function getRepository(): ActivityRepository
    {
        return $this->em->getRepository(Activity::class);
    }

    public function activityFormValidation($activity, $activityForm): array
    {
        $flashMessage = [
            'type' => '',
            'message' => ''
        ];
        if($activityForm->isSubmitted() && $activityForm->isValid())
        {
            $this->getEm()->persist($activity);
            $this->getEm()->flush();
        }

        return $flashMessage;
    }


}