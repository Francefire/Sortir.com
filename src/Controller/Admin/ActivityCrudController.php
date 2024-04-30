<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class ActivityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Activity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Activités')
            ->setEntityLabelInSingular('Activité');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('Identifiant')->onlyOnIndex(),
            TextField::new('name')->setLabel('Nom'),
            DateTimeField::new('startDatetime')->setLabel('Date de début'),
            DateTimeField::new('registerLimitDatetime')->setLabel('Date limite d\'enregistrement'),
            TimeField::new('duration')->setLabel('Durée'),
            IntegerField::new('maxParticipants')->setLabel('Nombre maximum de participants'),
            TextField::new('description')->setLabel('Description'),
            AssociationField::new('state')->setLabel('Status'),
            AssociationField::new('campus')->setLabel('Campus'),
            AssociationField::new('location')->setLabel('Lieu'),
            AssociationField::new('host')->setLabel('Organisateur'),
            AssociationField::new('participants')->setLabel('Participants'),
        ];
    }
}
