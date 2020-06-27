<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }
    /**
     * @Route("/testing",name="testing")
     */
    public function test()
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


        return $this->render('pre_sign_in/home2.html.twig', [
            "formContact" => $mailform->createView(),
            "formRegister" => $formRegister->createView(),
        ]);
    }
    /**
     * @Route("/loging",name="loginn")
     */
    public function logg()
    {
        return $this->render("pre_sign_in/login.html.twig");
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/calendar", name="booking_calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('event/calendar.html.twig');
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    
}
