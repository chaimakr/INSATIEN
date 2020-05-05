<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class ConnexionController extends AbstractController
{
    /**
     * @Route("/preLogin" ,name="preLogin")
     */
    public function preLogin(){
        if (isset($_POST['_username']) AND isset($_POST['_password'])){
            return $this->redirectToRoute("login", [
                'email' => $_POST['_username'],
                'password' => $_POST['_password']

            ]);}
    }
    
    
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
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
