<?php

namespace App\Controller;

use App\Entity\ContactMail;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
     * @Route("/", name="index")
     */
    public function index()
    {


        if ($this->getUser()) {
            $role = $this->getUser()->getRoles()[0];
//            dd($role);

            if ($role == 'ROLE_STUDENT') return $this->redirect('/student');
            elseif ($role == 'ROLE_TEACHER') return $this->redirect('/teacher');


            elseif ($role == "IS_AUTHENTICATED_ANONYMOUSLY") return $this->redirect('/anon');

        }


        return $this->redirect('/anon');
    }










    /**
     * @Route("/anon/event", name="event")
     */
    public function event()
    {
        return $this->render('pre_sign_in/event.html.twig');
    }










    /**
     * @Route("/anon", name="home")
     */
    public function home(Request $request, EntityManagerInterface $manager, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder)
    {



        $message = new ContactMail();
        $mailform = $this->createFormBuilder($message)
            ->add('fullName', TextType::class)
            ->add('email', TextType::class)
            ->add('Subject', ChoiceType::class, [
                'choices' => [
                    "Problem"=>"problem",
                    "Service" => "General Customer Service",
                    "Suggestions" => "Suggestions",
                    "Feedbacks" => "Feedbacks",

                ]
            ])
            ->add('Message', TextareaType::class)
            ->add('send', SubmitType::class)
            ->add('reset', ResetType::class)
            ->getForm();
        $mailform->handleRequest($request);

        if ($mailform->isSubmitted() && $mailform->isValid()) {
            $manager->persist($message);
            $manager->flush();
            $contact = $mailform->getData();
            $msg = (new \Swift_Message('Nouveau contact'))
                ->setFrom('insatien.help@gmail.com')
                ->setTo('insatien.help@gmail.com')
                ->setBody(
                    $this->renderView(
                        'mailing/mail.html.twig', compact('contact')
                    ),
                    'text/html'
                );
            $mailer->send($msg);
            return $this->redirect('feedback');
        }


        $compte = new User();
        $formRegister = $this->createFormBuilder($compte)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('password', PasswordType::class)
            ->add('confirmPassword', PasswordType::class)
            ->add('email', TextType::class)
            ->add('RegisterAs', ChoiceType::class, [
                "choices" => [
                    "teacher" => "teacher",
                    "student" => "student"
                ]
            ])
            ->add('register', SubmitType::class)
            ->getForm();
        try {
            $formRegister->handleRequest($request);
        } catch (\Exception $e) {
            echo "failed : " . $e->getMessage();
        }

        if ($formRegister->isSubmitted() && $formRegister->isValid()) {
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
            return $this->redirect('/anon/comfirmation?username=' . $compte->getFirstName());
            /*in case makhdemch arjaa chouf hkeyet return hedhi
            return $guardHandler->authenticateUserAndHandleSuccess(
                 $user,
                 $request,
                 $authenticator,
                 'main' // firewall name in security.yaml
             );*/
        }


        return $this->render('pre_sign_in/home.html.twig', [
            "formContact" => $mailform->createView(),
            "formRegister" => $formRegister->createView(),
        ]);
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

            sleep(1);

            $manager = $this->getDoctrine()->getManager();
            $user = $manager->getRepository("App:User")->findOneByEmail($request->getSession()->get(Security::LAST_USERNAME));

            if (!$user) {
                $this->addFlash('error', 'your email is not registred .');
            } else {
                if($user->getConfirmationCode()=="confirmed"){
                    $this->addFlash('error', 'your email is already confirmed  .');
                    return $this->redirect('/');
                }
                if ($form->get('confirmationCode')->getData() == $user->getConfirmationCode()) {
                    $user->setConfirmationCode('confirmed');
                    $manager->persist($user);
                    $manager->flush();
                    $this->addFlash('success', 'your email is confirmed , you can sign in now .');
                    return $this->redirect('/');
                } else
                    $this->addFlash('info', 'wrong confirmation code .');
            }

            return $this->redirect('/anon/comfirmation');


        }


        return $this->render('pre_sign_in/comfirmation.html.twig', [
            "username" => $request->getSession()->get(Security::LAST_USERNAME),
            "form" => $form->createView()
        ]);
    }










//    /**
//     * @Route("/anon/register", name="register")
//     */
//    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
//    {
////        $request->getSession()->clear();
//
//        $compte = new User();
//        $form = $this->createFormBuilder($compte)
//            ->add('firstName', TextType::class, [
//                "attr" => [
//                    "id" => "defaultRegisterFormFirstName",
//                    "class" => "form-control bg-white border-left-0 border-md",
//                    "placeholder" => "First name"
//                ]
//            ])
//            ->add('lastName', TextType::class, [
//                "attr" => [
//                    "id" => "defaultRegisterFormLastName",
//                    "class" => "form-control bg-white border-left-0 border-md",
//                    "placeholder" => "Last name"
//                ]
//            ])
//            ->add('password', PasswordType::class, [
//                "attr" => [
//                    "id" => "defaultRegisterFormPassword",
//                    "class" => "form-control bg-white border-left-0 border-md",
//                    "placeholder" => "Password",
//                    "aria-describedby" => "defaultRegisterFormPasswordHelpBlock"
//                ]
//            ])
//            ->add('confirmPassword', PasswordType::class, [
//                "attr" => [
//                    "id" => "defaultRegisterFormPassword",
//                    "class" => "form-control bg-white border-left-0 border-md",
//                    "placeholder" => "Confirm Password",
//                    "aria-describedby" => "defaultRegisterFormPasswordHelpBlock"
//                ]
//            ])
//            ->add('email', TextType::class, [
//                "attr" => [
//                    "id" => "defaultRegisterFormEmail",
//                    "class" => "form-control bg-white border-left-0 border-md",
//                    "placeholder" => "example@insat.u-carthage.tn"
//                ]
//            ])
//            ->add('RegisterAs', ChoiceType::class, [
//                "choices" => [
//                    "teacher" => "teacher",
//                    "student" => "student"
//                ], "attr" => [
//                    "class" => "form-control custom-select bg-white border-left-0 border-md"
//                ]
//            ])
//            ->add('register', SubmitType::class, [
//                "attr" => [
//                    "class" => "btn btn-success my-4 btn-block"
//                ]
//            ])
//            ->getForm();
//        try {
//            $form->handleRequest($request);
//        } catch (\Exception $e) {
//            echo "failed : " . $e->getMessage();
//        }
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//
//            $random_string = 'confirmed';
//            while ($random_string == 'confirmed') {
//                $random_string = '';
//                for ($i = 0; $i < 20; $i++) {
//                    $random_character = $input[mt_rand(0, strlen($input) - 1)];
//                    $random_string .= $random_character;
//                }
//            }
//
//            $compte->setConfirmationCode($random_string);
//            $hash = $encoder->encodePassword($compte, $compte->getPassword());
//            $compte->setPassword($hash);
//            $manager->persist($compte);
//            $manager->flush();
//            $session = new Session();
//            $session->set(Security::LAST_USERNAME, $compte->getEmail());
//            $this->forward('App\Controller\MailingController::ConfirmationMail', [
//                'email' => $compte->getEmail(),
//                'confirmationCode' => $compte->getConfirmationCode()
//            ]);
//            return $this->redirect('/anon/comfirmation?username=' . $compte->getFirstName());
//            /*in case makhdemch arjaa chouf hkeyet return hedhi
//            return $guardHandler->authenticateUserAndHandleSuccess(
//                 $user,
//                 $request,
//                 $authenticator,
//                 'main' // firewall name in security.yaml
//             );*/
//        }
//
//        return $this->render('pre_sign_in/register.html.twig', [
//            "form" => $form->createView()
//        ]);
//    }


    /**
     * @Route("/anon/confirmWithoutRegister", name="confirmWithoutRegister")
     */
    public function confirmWithoutRegister(Request $request)
    {



        $user = $this->getDoctrine()->getManager()->getRepository('App:User')->findByEmail($request->get('email'));
        if ($user) {
            if($user[0]->getConfirmationCode()=='confirmed'){
                $this->addFlash('error',"your email is already confirmed .");
                return $this->redirect('/');
            }
            $request->getSession()->set(Security::LAST_USERNAME, $request->get('email'));

            return $this->redirect('/anon/comfirmation');
        }

        $this->addFlash('error',"your email is not registred .");
        return $this->redirect('/');


    }










    /**
     * @Route("/anon/resendConfirmation", name="resendConfirmation")
     */
    public function resendConfirmation(Request $request)
    {


        $manager = $this->getDoctrine()->getManager();

        $user = $manager->getRepository("App:User")->findOneByEmail($request->getSession()->get(Security::LAST_USERNAME));

        $this->forward('App\Controller\MailingController::ConfirmationMail', [
            'email' => $user->getEmail(),
            'confirmationCode' => $user->getConfirmationCode()
        ]);
        return $this->redirect('/comfirmation');
    }


}

?>