<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Group;
use App\Entity\State;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sortir.com - Administration')
            ->disableDarkMode();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute('Retourner sur le site', 'fa fa-reply', 'main_home'),

            MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home'),

            MenuItem::linkToCrud('Activité', 'fa fa-user', Activity::class),
            MenuItem::linkToCrud('Statut', 'fa fa-user', State::class),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Campus', 'fa fa-industry', Campus::class),
            MenuItem::linkToCrud('Ville', 'fa fa-building', City::class),
            MenuItem::linkToCrud('Groupes', 'fa fa-building', Group::class),
        ];
    }
}
