<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\File;


class UserController extends AbstractController
{


    /**
     * @Route("/user/profile", name="profile")
     */
    public function Profile(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {

        $user = $this->getUser();


        $formUploadPic = $this->createFormBuilder()
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '6000k',
                        'mimeTypes' => [
                            'image/*',

                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('changePicture', SubmitType::class)
            ->getForm();

        $formUploadPic->handleRequest($request);
        if ($formUploadPic->isSubmitted() && $formUploadPic->isValid()) {
            $uploadedFile = $formUploadPic['picture']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
//            dd($uploadedFile);

            $uploadedFile->move($destination, 'picture' . $user->getId() . '.jpg');
            $manager->getRepository('App:User')->findOneById($user->getId());
            $user->setPdpPath($destination . '/picture' . $user->getId() . '.jpg');
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'your profile picture has changed .');
            return $this->redirect('/user/profile');
        }

        if (isset($_POST["firstName"]) && $_POST["firstName"]) {
            $user->setFirstName($_POST["firstName"]);
            $this->addFlash('success', 'your profile is up to date');
        }
        if (isset($_POST["lastName"]) && $_POST["lastName"]) {
            $user->setLastName($_POST["lastName"]);
            $this->addFlash('success', 'your profile is up to date');
        }
        if (isset($_POST["newPassword"]) && $_POST["newPassword"]) {
            if ((strlen($_POST["newPassword"]) > 7)) {
                if (isset($_POST["currentPassword"]) && $_POST["currentPassword"]) {
                    $hash = $encoder->encodePassword($user, $_POST["currentPassword"]);
                    if ($user->getPassword() == $hash) {
                        $hash = $encoder->encodePassword($user, $_POST["newPassword"]);
                        $user->setPassword($hash);
                        $this->addFlash('success', 'your profile is up to date');
                    } else {
                        $this->addFlash('error', 'that\'s not your current password. try again !');
                    }
                } else {
                    $this->addFlash('error', 'tap your current password first.');
                }
            } else {
                $this->addFlash('error', 'your new password must contain at least 8 characters !');
            }
        }
        if (isset($_POST["email"]) && $_POST["email"]) {
            if (preg_match("#@insat.u-carthage.tn#", $_POST["email"])) {
                $user->setEmail($_POST["email"]);
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
                $session = new Session();
                $session->set(Security::LAST_USERNAME, $user->getEmail());
                return $this->redirect('/anon/comfirmation');

            } else {
                $this->addFlash('error', 'you have to use an insat.u-carthage email : example@insat.u-carthage.tn');
            }
        }
        $manager->persist($user);
        $manager->flush();
        return $this->render("user/userProfile.html.twig", [
            'formUploadPic' => $formUploadPic->createView()
        ]);
    }










    /**
     * @Route("/test", name="test")
     */
    public function test(PublisherInterface $publisher)
    {

        $update = new Update('newRequest4', "[]");

        // The Publisher service is an invokable object
        $publisher($update);

        return new Response("done");

    }










    /**
     * @Route("/user/requestNotification", name="requestNotification")
     */
    public function requestNotification(EntityManagerInterface $manager)
    {

        if (in_array('ROLE_TEACHER', $this->getUser()->getRoles())) {
            $requests = $manager->getRepository('App:RequestFromStudent')->findByClassGroup(
                $manager->getRepository('App:ClassGroup')->findByOwner($this->getUser()), ['id' => 'desc']
            );

        } elseif (in_array('ROLE_STUDENT', $this->getUser()->getRoles())) {

            $requests = $manager->getRepository('App:RequestFromTeacher')->findByStudent($this->getUser(), ['id' => 'desc']);
        }


        return $this->render('user/requestNotification.html.twig', [
            'requests' => $requests,
            'caller' => $this->getUser()->getRoles()[0]
        ]);


    }










    /**
     * @Route("/user/showRequests", name="showRequests")
     */
    public function showRequests(EntityManagerInterface $manager)
    {

        if($this->getUser()->getRoles()[0]=='ROLE_TEACHER'){
            $requests = $manager->getRepository('App:RequestFromStudent')->findByClassGroup(
                $manager->getRepository('App:ClassGroup')->findByOwner($this->getUser()), ['id' => 'desc']
            );

        }elseif($this->getUser()->getRoles()[0]=='ROLE_STUDENT'){
            $requests = $manager->getRepository('App:RequestFromTeacher')->findByStudent($this->getUser(),['id'=>'desc']);

        }




        return $this->render("user/showRequests.html.twig", [
            'requests' => $requests
        ]);
    }


}
