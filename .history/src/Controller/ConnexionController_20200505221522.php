<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;


class ConnexionController extends AbstractController
{
 
    
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request ,AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('connexion/login.html.twig',[
            'last_username' => $lastUsername,
            'error'         => $error,

        ]);
    }

    /**
     * @Route("/account", name="account")
     */
    public function connected(){
       // if (isStudent){render(student homepage)}
       //else{render(lel prof homepage)}
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('pre_sign_in/home.html.twig');
    }
}
