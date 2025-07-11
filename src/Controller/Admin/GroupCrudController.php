<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Group::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Groupes')
            ->setEntityLabelInSingular('Groupe');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('Identifiant')->onlyOnIndex(),
            TextField::new('name')->setLabel('Nom'),
            AssociationField::new('leader')->setLabel('Chef'),
            AssociationField::new('participants')->setLabel('Participants'),
        ];
    }
}
