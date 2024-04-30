<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('Identifiant')->hideOnForm(),
            TextField::new('username')->setLabel('Nom d\'utilisateur'),
            TextField::new('plainPassword')->setLabel('Mot de passe')->onlyOnForms(),
            TextField::new('phone')->setLabel('Numéro de téléphone'),
            EmailField::new('email')->setLabel('Email'),
            TextField::new('lastname')->setLabel('Nom'),
            TextField::new('firstname')->setLabel('Prénom'),
            AssociationField::new('campus')->setLabel('Campus')->onlyOnIndex(),
            ArrayField::new('roles')->setLabel('Rôles'),
            BooleanField::new('administrator')->setLabel('Administrateur'),
            BooleanField::new('disabled')->setLabel('Désactivé'),
        ];
    }
}
