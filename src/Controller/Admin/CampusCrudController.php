<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CampusCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Campus::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Campus')
            ->setEntityLabelInSingular('Campus');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('Identifiant')->onlyOnIndex(),
            TextField::new('name')->setLabel('Nom'),
        ];
    }
}
