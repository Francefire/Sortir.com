<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Service\FileService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use Symfony\Component\HttpFoundation\Response;

class ActivityCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly FileService $fileService
    )
    {
    }

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

    public function configureActions(Actions $actions): Actions
    {
        $deleteImage = Action::new('deleteImage', 'Delete image')
            ->linkToCrudAction('deleteImage');
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, $deleteImage);
    }

    public function deleteImage(AdminContext $context): Response
    {
        $activity = $context->getEntity()->getInstance();
        $this->fileService->remove('/images', $activity->getImageFileName());

        return $this->redirectToRoute($context->getDashboardRouteName());
    }
}
