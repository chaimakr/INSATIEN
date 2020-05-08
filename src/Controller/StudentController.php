<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student")
     */
    public function index()
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }




    /**
     * @Route("/student/connected", name="main")
     */
    public function main()
    {
//        dd($this->getUser());
        return $this->render("StudentConnected.html.twig");
    }
}
