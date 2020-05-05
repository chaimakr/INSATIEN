<?php

namespace App\Controller;

use App\Entity\NewCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PreSignInController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index()
    {
        return $this->render('default/event.html.twig');
    }
    
    /** 
    * @Route("/", name="home")
    */
    public function home(){
        return $this->render('default/home.html.twig');
    }

    /**
     * @Route("/comfirmation", name="comfirmation")
     */
    public function comfirmation(Request $request){
        return $this->render('default/comfirmation.html.twig',[
            "username"=>$request->get('username')
        ]);
    }

    /** 
    * @Route("/register", name="register")
    */
    public function register(Request $request, EntityManagerInterface $manager , UserPasswordEncoderInterface $encoder){

        $compte=new NewCompte();
        $form=$this->createFormBuilder($compte)
        ->add('firstName',TextType::class,[
            "attr"=>[
                "id"=>"defaultRegisterFormFirstName",
                "class"=>"form-control", 
                "placeholder"=>"First name"
            ]
        ])
        ->add('lastName',TextType::class,[
            "attr"=>[
                "id"=>"defaultRegisterFormLastName",
                "class"=>"form-control", 
                "placeholder"=>"Last name"
            ]
        ])
        ->add('password',PasswordType::class,[
            "attr"=>[
                "id"=>"defaultRegisterFormPassword",
                "class"=>"form-control", 
                "placeholder"=>"Password",
                "aria-describedby"=>"defaultRegisterFormPasswordHelpBlock"
            ]
        ])
        ->add('confirmPassword',PasswordType::class,[
            "attr"=>[
                "id"=>"defaultRegisterFormPassword",
                "class"=>"form-control", 
                "placeholder"=>"Confirm Password",
                "aria-describedby"=>"defaultRegisterFormPasswordHelpBlock"
            ]
        ])
        ->add('email',TextType::class,[
            "attr"=>[
                "id"=>"defaultRegisterFormEmail",
                "class"=>"form-control mb-4",
                "placeholder"=>"example@insat.u-carthage.tn"
            ]
        ])
        ->add('RegisterAs',ChoiceType::class,[
            "choices"=>[
                "teacher"=>"teacher",
                "student"=>"student"
            ],"attr"=>[
                "class"=>"browser-default custom-select mb-4"
            ]
        ])
        ->add('sign up',SubmitType::class,[
            "attr"=>[
                "class"=>"btn btn-success my-4 btn-block" 
            ]
        ])
        ->getForm();
        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            echo "failed : ".$e->getMessage();
        }
        
        if($form->isSubmitted() && $form->isValid()){
            $input='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $input_length = strlen($input);
            $random_string = '';
            for($i = 0; $i < 8; $i++) {
                $random_character = $input[mt_rand(0, $input_length - 1)];
                $random_string .= $random_character;
            }
            $compte->setConfirmationCode($random_string);
            $compte->setTried(false);
            $hash = $encoder->encodePassword($compte, $compte->getPassword());
            $compte->setPassword($hash);
            $manager->persist($compte);
            $manager->flush();
            return $this->redirect('/comfirmation?username='.$compte->getFirstName());
        }

        return $this->render('default/register.html.twig',[
            "form"=>$form->createView()
        ]);
        }
}
?>