<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

class PreSignInController extends AbstractController
{
    /**
     * @Route("/anon/event", name="event")
     */
    public function index()
    {
        return $this->render('pre_sign_in/event.html.twig');
    }

    /**
     * @Route("/anon/", name="home")
     */
    public function home()
    {
        dd();
        return $this->render('pre_sign_in/home.html.twig');
    }

    /**
     * @Route("/anon/comfirmation", name="comfirmation")
     */
    public function comfirmation(Request $request)
    {

        $form = $this->createFormBuilder()
            ->add('confirmationCode', TextType::class, [
                "attr" => [
                    "placeholder" => "confirmation code"
                ]
            ])
            ->add('confirm', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $user = $manager->getRepository("App:User")->findOneByEmail($request->getSession()->get(Security::LAST_USERNAME));

            if(!$user){
                return $this->render("pre_sign_in/message.html.twig", [
                    'message' => 'your email is not registred .'
                ]);
            }
            else{
                if ($form->get('confirmationCode')->getData() == $user->getConfirmationCode()) {
                    $user->setConfirmationCode('confirmed');
                    $manager->persist($user);
                    $manager->flush();
                    return $this->render("pre_sign_in/message.html.twig", [
                        'message' => 'your email is confirmed .'
                    ]);
                } else return $this->render("pre_sign_in/message.html.twig", [
                    'message' => 'wrong confirmation code .'
                ]);
            }


        }


        return $this->render('pre_sign_in/comfirmation.html.twig', [
            "username" => $request->get('username'),
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/anon/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
//        $request->getSession()->clear();

        $compte = new User();
        $form = $this->createFormBuilder($compte)
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
            ->add('password', PasswordType::class, [
                "attr" => [
                    "id" => "defaultRegisterFormPassword",
                    "class" => "form-control",
                    "placeholder" => "Password",
                    "aria-describedby" => "defaultRegisterFormPasswordHelpBlock"
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                "attr" => [
                    "id" => "defaultRegisterFormPassword",
                    "class" => "form-control",
                    "placeholder" => "Confirm Password",
                    "aria-describedby" => "defaultRegisterFormPasswordHelpBlock"
                ]
            ])
            ->add('email', TextType::class, [
                "attr" => [
                    "id" => "defaultRegisterFormEmail",
                    "class" => "form-control mb-4",
                    "placeholder" => "example@insat.u-carthage.tn"
                ]
            ])
            ->add('RegisterAs', ChoiceType::class, [
                "choices" => [
                    "teacher" => "teacher",
                    "student" => "student"
                ], "attr" => [
                    "class" => "browser-default custom-select mb-4"
                ]
            ])
            ->add('sign up', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-success my-4 btn-block"
                ]
            ])
            ->getForm();
        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            echo "failed : " . $e->getMessage();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $random_string = 'confirmed';
            while ($random_string == 'confirmed') {
                $random_string = '';
                for ($i = 0; $i < 20; $i++) {
                    $random_character = $input[mt_rand(0, strlen($input) - 1)];
                    $random_string .= $random_character;
                }
            }

            $compte->setConfirmationCode($random_string);
            $hash = $encoder->encodePassword($compte, $compte->getPassword());
            $compte->setPassword($hash);
            $manager->persist($compte);
            $manager->flush();
            $session = new Session();
            $session->set(Security::LAST_USERNAME, $compte->getEmail());
            $this->forward('App\Controller\MailingController::ConfirmationMail', [
                'email' => $compte->getEmail(),
                'confirmationCode' => $compte->getConfirmationCode()
            ]);
            return $this->redirect('/comfirmation?username=' . $compte->getFirstName());
           /*in case makhdemch arjaa chouf hkeyet return hedhi 
           return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );*/
        }

        return $this->render('pre_sign_in/register.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/anon/confirmWithoutRegister", name="confirmWithoutRegister")
     */
    public function confirmWithoutRegister(Request $request)
    {

        $request->getSession()->set(Security::LAST_USERNAME, $request->get('email'));
        return $this->redirect('/comfirmation');
    }

    /**
     * @Route("/resendConfirmation", name="resendConfirmation")
     */
    public function resendConfirmation(Request $request)
    {
        $manager=$this->getDoctrine()->getManager();

        $user = $manager->getRepository("App:User")->findOneByEmail($request->getSession()->get(Security::LAST_USERNAME));

        $this->forward('App\Controller\MailingController::ConfirmationMail', [
            'email' => $user->getEmail(),
            'confirmationCode' => $user->getConfirmationCode()
        ]);
        return $this->redirect('/comfirmation');
    }


}

?>