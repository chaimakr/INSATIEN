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
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
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
        $manager = $this->getDoctrine()->getManager();
        $covoiturages = $manager->getRepository('App:Covoiturage')->findRecent();
        $questions = $manager->getRepository('App:Question')->findRecent();
        return $this->render("student/StudentConnected.html.twig", [
            'covoiturages' => $covoiturages,
            'questions' => $questions
        ]);
    }










    /**
     * @Route("/student/showClasses", name="studentShowClasses")
     */
    public function showClasses(EntityManagerInterface $manager)
    {
        $otherClasses = $manager->getRepository('App:ClassGroup')->findAll();

        foreach ($otherClasses as $key => $class) {

            if ($class->getStudentsMembers()->contains($this->getUser())) {
                unset($otherClasses[$key]);
            }
        }


        return $this->render("student/showClasses.html.twig", [
            'classes' => $otherClasses
        ]);
    }










    /**
     * @Route("/student/joinClass/{id}", name="joinClass")
     */
    public function joinClass($id = null, EntityManagerInterface $manager, PublisherInterface $publisher)
    {
        $class = $manager->getRepository('App:ClassGroup')->findOneById($id);

        if (($class) && !($class->getStudentsMembers()->contains($this->getUser()))) {


            $allRequests =array_merge( $manager->getRepository('App:RequestFromStudent')->findAll(),
                $manager->getRepository('App:RequestFromTeacher')->findAll()

            );
            foreach ($allRequests as $request) {
                if ($request->getStudent() == $this->getUser() && $request->getClassGroup() == $class) {
                    $this->addFlash('info',
                        'You already sent a request to join' . $class->getTitle()
                    .' or the teacher already invited you to join, please check your requests '
                       );
                    return $this->redirect('/student/showClasses');
                }
            }

            $requestJoin = new \App\Entity\RequestFromStudent();
            $requestJoin->setStudent($this->getUser());
            $requestJoin->setClassGroup($class);
            $manager->persist($requestJoin);
            $manager->flush();
            $update = new Update('newRequest' . $class->getOwner()->getId(), "[]");
            $publisher($update);
            $this->addFlash('success', 'Your request to join ' . $class->getTitle() . ' has been send successfully.');
            return $this->redirect('/student/showClasses');
        } else {
            $this->addFlash('error', 'Class not found .');
            return $this->redirect('/student/showClasses');

        }


    }










    /**
     * @Route("/student/myClasses", name="myClasses")
     */
    public function myClasses(EntityManagerInterface $manager)
    {

        $classes = $manager->getRepository('App:User')->findOneById($this->getUser()->getId())->getStudentClassGroups();


        return $this->render("student/myClasses.html.twig", [
            'classes' => $classes
        ]);
    }













}