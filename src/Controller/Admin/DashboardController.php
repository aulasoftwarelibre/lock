<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\OrganizationUnit;
use App\Entity\Secret;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private CrudUrlGenerator $crudUrlGenerator;

    public function __construct(CrudUrlGenerator $crudUrlGenerator)
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->crudUrlGenerator->build();

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Lock');
    }

    /**
     * {@inheritdoc}
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Catalog');
        yield MenuItem::linkToCrud('Usuarios', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Grupos', 'fa fa-users', OrganizationUnit::class);
        yield MenuItem::linkToCrud('Contraseñas', 'fa fa-lock', Secret::class);
    }
}
