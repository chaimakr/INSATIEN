<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;


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
    public function StudentProfile(Request $request, UserPasswordEncoderInterface $encoder , EntityManagerInterface $manager)
    {
       // dd($this->getUser());
        $user = $this->getUser();
        
       if (isset($_POST["firstName"])&&$_POST["firstName"]){
            $user->setFirstName($_POST["firstName"]);
       }
       if (isset($_POST["lastName"])&&$_POST["lastName"]){
            $user->setLastName($_POST["lastName"]) ;
       }
       if(isset($_POST["newPassword"])&&$_POST["newPassword"]){
            if((strlen($_POST["newPassword"])>7)){  
                if(isset($_POST["currentPassword"])&&$_POST["currentPassword"]){
                        $hash = $encoder->encodePassword($user, $_POST["currentPassword"]);
                        if($user->getPassword()==$hash){
                                $hash = $encoder->encodePassword($user, $_POST["newPassword"]);
                                $user->setPassword($hash);
                        }else{
                            $this->addFlash('error','that\'s not your current password. try again !');
                        }
                }else{
                    $this->addFlash('error','tap your current password first.');
                }
            }else{
                $this->addFlash('error','your new password must contain at least 8 characters !');
            }
        }
       if (isset($_POST["email"])&&$_POST["email"]){
        if (preg_match("#@insat.u-carthage.tn#",$_POST["email"])){
            $user->setEmail($_POST["email"]) ;
            $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $random_string = 'confirmed';
            while ($random_string == 'confirmed') {
                $random_string = '';
                for ($i = 0; $i < 20; $i++) {
                    $random_character = $input[mt_rand(0, strlen($input) - 1)];
                    $random_string .= $random_character;
                }
            }
            $user->setConfirmationCode($random_string);
            $manager->persist($user);
            $manager->flush();
            $this->forward('App\Controller\MailingController::resendConfirmation');
        }
       else{
           $this->addFlash('error','you have to use an insat.u-carthage email : example@insat.u-carthage.tn');
       }
     }
        $manager->persist($user);
        $manager->flush();
        return $this->render("userProfile.html.twig");
    }
}
