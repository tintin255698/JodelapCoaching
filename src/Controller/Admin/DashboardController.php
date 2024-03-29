<?php

namespace App\Controller\Admin;

use App\Entity\CoachingTarif;
use App\Entity\Coffret;
use App\Entity\Commentaire;
use App\Entity\Evenement;
use App\Entity\Header;
use App\Entity\Image;
use App\Entity\ResaCoaching;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(EvenementCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Jodelap Coaching');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Évènements', 'fa fa-calendar-week', Evenement::class);
        yield MenuItem::linkToCrud('Coaching', 'fas fa-chart-area', CoachingTarif::class);
        yield MenuItem::linkToCrud('Coffrets cadeaux', 'fas fa-gifts', Coffret::class);
        yield MenuItem::linkToCrud('Galerie', 'fa fa-images', Image::class);
        yield MenuItem::linkToCrud('Avis', 'fa fa-comment', Commentaire::class);
        yield MenuItem::linkToCrud('Réservation', 'fas fa-book-open', ResaCoaching::class);
    }
}
