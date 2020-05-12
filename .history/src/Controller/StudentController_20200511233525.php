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
        $form = $this->createFormBuilder($user)
        ->add('firstName', TextType::class, [
            "attr" => [
                "id" => "defaultRegisterFormFirstName",
                "class" => "form-control"
            ]
        ])
        ->add('lastName', TextType::class, [
            "attr" => [
                "id" => "defaultRegisterFormLastName",
                "class" => "form-control"
            ]
        ])
        ->add('email', TextType::class, [
            "attr" => [
                "id" => "defaultRegisterFormEmail",
                "class" => "form-control mb-4",
                "placeholder" => "example@insat.u-carthage.tn"
            ]
        ])
        /*->add('password', PasswordType::class, [
            "attr" => [
                "id" => "defaultRegisterFormPassword",
                "class" => "form-control",
                "placeholder" => "Current password",
                "aria-describedby" => "defaultRegisterFormPasswordHelpBlock"
            ]
        ])*/
        ->add('password', PasswordType::class, [
            "attr" => [
                "id" => "defaultRegisterFormPassword",
                "class" => "form-control",
                "placeholder" => "New password",
                "aria-describedby" => "defaultRegisterFormPasswordHelpBlock"
            ]
        ])
        ->add('Save changes', SubmitType::class, [
            "attr" => [
                "class" => "btn btn-success my-4 btn-block"
            ]
        ])
        ->add('Cancel', ResetType::class, [
            "attr" => [
                "class" => "btn btn-warning my-4 btn-block"
            ]
        ])
        ->getForm();
        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            echo "failed : " . $e->getMessage();
        }

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
     }*/
        $manager->persist($user);
        $manager->flush();
        return $this->render("userProfile.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
