<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
//use App\Entity\Response;
use App\Entity\User;
use App\Entity\Note;
use App\Entity\Event;
use App\Entity\Covoiturage;
use App\Entity\ClassGroup;
use App\Entity\Quiz;
use App\Entity\Report;



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
            ->setTitle('INSATIEN');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
        yield    MenuItem::section('Users');
        yield    MenuItem::linkToCrud('User', 'fa fa-user', User::class);

        yield    MenuItem::section('Features');
        yield    MenuItem::linkToCrud('Classes', 'fa fa-comment', ClassGroup::class);
        yield    MenuItem::linkToCrud('Questions', 'fa fa-commenting', Question::class);
        //yield    MenuItem::linkToCrud('Questions', 'fa fa-commenting', Response::class);
        yield    MenuItem::linkToCrud('Notes', 'fa fa-file-text', Note::class);
        yield    MenuItem::linkToCrud('Carpoolings', 'fa fa-car', Covoiturage::class);
        yield    MenuItem::linkToCrud('Quizs', 'fa fa-check-square-o', Quiz::class);



        yield    MenuItem::section('Calendar');
        yield    MenuItem::linkToCrud('Events', 'fa fa-calendar', Event::class);
    }
}
