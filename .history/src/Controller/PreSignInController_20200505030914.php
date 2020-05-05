<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PreSignInController extends AbstractController
{
    /**
     * @Route("/pre/sign/in", name="pre_sign_in")
     */
    public function index()
    {
        return $this->render('pre_sign_in/index.html.twig', [
            'controller_name' => 'PreSignInController',
        ]);
    }
}
