<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\FileService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Response;


class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly FileService $fileService
    )
    {
    }

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
            TextField::new('plainPassword')->setLabel('Mot de passe')->setRequired(true)->setFormType(PasswordType::class)->onlyOnForms(),
            TextField::new('phone')->setLabel('Numéro de téléphone'),
            EmailField::new('email')->setLabel('Email'),
            TextField::new('lastname')->setLabel('Nom'),
            TextField::new('firstname')->setLabel('Prénom'),
            AssociationField::new('campus')->setLabel('Campus'),
            ArrayField::new('roles')->setLabel('Rôles'),
            BooleanField::new('administrator')->setLabel('Administrateur'),
            BooleanField::new('disabled')->setLabel('Désactivé'),
            ImageField::new('avatar')->setBasePath('public/uploads/avatars')->onlyOnDetail(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $deleteAvatar = Action::new('deleteAvatar', 'Delete avatar')
            ->linkToCrudAction('deleteAvatar');
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, $deleteAvatar);
    }

    public function deleteAvatar(AdminContext $context): Response
    {
        $user = $context->getEntity()->getInstance();
        $this->fileService->remove('/avatars', $user->getAvatarFilename());

        return $this->redirectToRoute('');
    }
}
