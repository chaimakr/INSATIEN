<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/anon/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
       //$user=$this->getUser();
        //if($user){
        //    if ($user()->getRegisterAs()=="student") {
        //    return $this->redirectToRoute('main');
        //}
        //elseif ($this->getUser()->getRegisterAs() == 'teacher'){
        //return $this->redirectToRoute('main2');
        //}
        //}
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user

        $lastUsername = $authenticationUtils->getLastUsername();

//        dd($lastUsername);
//        return $this->render('base.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
//        dd($error);



        if($error)
        $this->addFlash('loginError','Invalid Credentials');
        return $this->redirect("/anon");
    }




    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }










}
