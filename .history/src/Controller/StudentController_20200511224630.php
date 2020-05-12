<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


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
    public function StudentProfile(UserPasswordEncoderInterface $encoder , EntityManagerInterface $manager)
    {
       // dd($this->getUser());
        $user = new User();
        $form = $this->createFormBuilder($user
        ->add('firstName', TextType::class, [
            "attr" => [
                "id" => "defaultRegisterFormFirstName",
                "class" => "form-control",
                "placeholder" => "First name"
            ]
        ])
        ->add('lastName', TextType::class, [
            "attr" => [
                "id" => "defaultRegisterFormLastName",
                "class" => "form-control",
                "placeholder" => "Last name"
            ]
        ])
        ->add('email', TextType::class, [
            "attr" => [
                "id" => "defaultRegisterFormEmail",
                "class" => "form-control mb-4",
                "placeholder" => "example@insat.u-carthage.tn"
            ]
        ])
        ->add('currentpassword', PasswordType::class, [
            "attr" => [
                "id" => "defaultRegisterFormPassword",
                "class" => "form-control",
                "placeholder" => "Current password",
                "aria-describedby" => "defaultRegisterFormPasswordHelpBlock"
            ]
        ])
        ->add('newPassword', PasswordType::class, [
            "attr" => [
                "id" => "defaultRegisterFormPassword",
                "class" => "form-control",
                "placeholder" => "New password",
                "aria-describedby" => "defaultRegisterFormPasswordHelpBlock"
            ]
        ])

       /*if (isset($_POST["firstName"])){
          $user->setFirstName($_POST["firstName"]);
       }
       if (isset($_POST["lastName"])){
        $user->setLastName($_POST["lastName"]) ;
       }
       if(isset($_POST["currentPassword"])&& isset($_POST["newPassword"])){
            $hash = $encoder->encodePassword($user, $_POST["currentPassword"]);
                if($user->getPassword()==$hash){
                    $hash = $encoder->encodePassword($user, $_POST["newPassword"]);
                    $user->setPassword($hash);
            }
       }
       if (isset($_POST["email"])){
        if (preg_match("#@insat.u-carthage.tn#",$_POST["email"])){
            $user.setEmail($_POST["email"]) ;
            $this->forward('App\Controller\PreSignInController::confirmWithoutRegister');
        }
       else{
           $this->addFlash('error','you have to use an insat.u-carthage email : example@insat.u-carthage.tn');
       }
     }
        $manager->persist($user);
        $manager->flush();*/
        return $this->render("userProfile.html.twig");
    }
}
