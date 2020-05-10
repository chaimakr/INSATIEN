<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{



    /**
     * @Route("/student", name="studentHome")
     */
    public function main()
    {
//        dd($this->getUser());
        return $this->render("student/StudentConnected.html.twig");
    }
      /**
     * @Route("/student/profile", name="profile")
     */
    public function StudentProfile()
    {
       // dd($this->getUser());
        $user=$this->getUser()
       if (isset($_POST["firstName"])){
          $user.setFirstName($_POST["firstName"]) ;
       }
       if isset($_POST["lastName"]){
        $user.setLastName($_POST["lastName"]) ;
     }
        return $this->render("userProfile.html.twig");
    }
}
