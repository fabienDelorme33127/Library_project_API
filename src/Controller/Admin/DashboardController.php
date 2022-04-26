<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\Nationalite;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Library Project API');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Lives', 'fas fa-list', Livre::class);
        yield MenuItem::linkToCrud('Auteurs', 'fas fa-list', Auteur::class);
        yield MenuItem::linkToCrud('Genres', 'fas fa-list', Genre::class);
        yield MenuItem::linkToCrud('Editeurs', 'fas fa-list', Editeur::class);
        yield MenuItem::linkToCrud('Nationnalités', 'fas fa-list', Nationalite::class);
    }
}
