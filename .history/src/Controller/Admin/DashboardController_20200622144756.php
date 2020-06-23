<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Entity\User;

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
            ->setTitle('Insatien');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
        yield    MenuItem::section('Users');
        yield    MenuItem::linkToCrud('User', 'fa fa-tags', User::class);
           // MenuItem::linkToCrud('Blog Posts', 'fa fa-file-text', BlogPost::class),

        yield    MenuItem::section('Classes');
        yield    MenuItem::linkToCrud('Questions', 'fa fa-comment', Question::class);
        yield    MenuItem::linkToCrud('Responses', 'fa fa-file-text', Response::class);
    }
}
