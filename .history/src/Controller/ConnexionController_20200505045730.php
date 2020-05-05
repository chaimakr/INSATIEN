<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('inc/navbar.html.twig');
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('default/home.html.twig');
    }
}
