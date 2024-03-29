<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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
    public function StudentProfile(UserPasswordEncoderInterface $encoder)
    {
       // dd($this->getUser());
        $user=$this->getUser();
       if (isset($_POST["firstName"])){
          $user->setFirstName($_POST["firstName"]) ;
       }
       if (isset($_POST["lastName"])){
        $user->setLastName($_POST["lastName"]) ;
       }
       if(isset($_POST["currentPassword"])&& isset($_POST["newPassword"])){
        $hash = $encoder->encodePassword($user, $_POST["currentPassword"]);
        if($user)
       }
       if (isset($_POST["email"])){
        if (preg_match("#@insat.u-carthage.tn#",$_POST["email"])){
            $user.setEmail($_POST["email"]) ;
        }
       else{
           //flashmsg
       }
     }
        return $this->render("userProfile.html.twig");
    }
}
