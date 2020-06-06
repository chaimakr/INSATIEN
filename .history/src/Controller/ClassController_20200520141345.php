<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClassController extends AbstractController
{
    /**
     * @Route("/class", name="class")
     */
    public function index()
    {
        return $this->render('class/index.html.twig', [
            'controller_name' => 'ClassController',
        ]);
    }
}
