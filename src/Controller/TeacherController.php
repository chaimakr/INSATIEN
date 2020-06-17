<?php

namespace App\Controller;

use App\Entity\ClassGroup;
use App\Entity\RequestFromTeacher;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher", name="teacherHome")
     */
    public function main(Request $request)
    {

//        $manager=$this->getDoctrine()->getManager();
//
//        for($i=0;$i<100;$i++){
//            $class=new ClassGroup();
//            $class->setTitle('classe'.$i);
//            $class->setOwner($this->getUser());
//        }


        return $this->render("teacher/TeacherConnected.html.twig");
    }










    /**
     * @Route("/teacher/addClass", name="addClass")
     */
    public function addClass(Request $request, EntityManagerInterface $manager, \Swift_Mailer $mailer, PublisherInterface $publisher)
    {


        $class = new ClassGroup();
        $class->setOwner($this->getUser());


        $formAddClass = $this->createFormBuilder($class)
            ->add('title')
            ->add('description')
            ->add('students', HiddenType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $formAddClass->handleRequest($request);
        if ($formAddClass->isSubmitted() && $formAddClass->isValid()) {


            $studentsIds = explode(',', $formAddClass->get('students')->getData());

            $studentsIds = array_filter($studentsIds, "ctype_digit");

            foreach ($studentsIds as $studentId) {
                $student = $manager->getRepository('App:User')->findOneById($studentId);
                if ($student) {
                    $invitation = new RequestFromTeacher();
                    $invitation->setStudent($student);
                    $invitation->setClassGroup($class);
                    $manager->persist($invitation);
                    $update = new Update('newRequest' . $student->getId(), "[]");
                    $publisher($update);

                }
            }

            $manager->persist($class);
            $manager->flush();


            $this->addFlash('success', 'class added successfully');
            return $this->redirect('/teacher/showClasses');

        }

        return $this->render("teacher/addClass.html.twig", [
            'formAddClass' => $formAddClass->createView(),
            'students' => $manager->getRepository('App:User')->findByRegisterAs("student")
        ]);
    }










    /**
     * @Route("/teacher/showClasses", name="TeacherShowClasses")
     */
    public function showClasses(EntityManagerInterface $manager)
    {



        $classes = $manager->getRepository('App:ClassGroup')->findByOwner($this->getUser());


//        $classes[0]->addStudentsMember($manager->getRepository('App:User')->findOneById(4));
//        $manager->persist($classes[0]);
//        $manager->flush();
//        dd();

        return $this->render("teacher/showClasses.html.twig", [
            'classes' => $classes
        ]);
    }










    /**
     * @Route("/teacher/modifyClass/{classId}", name="TeacherModifyClasse")
     */
    public function modifyClass(Request $request, EntityManagerInterface $manager, $classId = null)
    {

        $class = $manager->getRepository('App:ClassGroup')->findOneById($classId);


        if (!($class && $class->getOwner() == $this->getUser())) {
            $this->addFlash("error", "you don't own this class");
            return $this->redirect('/teacher/showClasses');
        }

        $formModifyClass = $this->createFormBuilder($class)
            ->add('title')
            ->add('description')
            ->add('students', HiddenType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $formModifyClass->handleRequest($request);

        $students = $manager->getRepository('App:ClassGroup')->findOneById($classId)->getStudentsMembers();

        if ($formModifyClass->isSubmitted() && $formModifyClass->isValid()) {


            foreach ($students as $student) {
                $class->removeStudentsMember($student);
            }


            $studentsIds = explode(',', $formModifyClass->get('students')->getData());

            $studentsIds = array_filter($studentsIds, "ctype_digit");

            foreach ($studentsIds as $studentId) {
                $student = $manager->getRepository('App:User')->findOneById($studentId);

                $class->addStudentsMember($student);

            }

            $manager->persist($class);
            $manager->flush();


            return $this->redirect('/teacher/showClasses');
        }

        return $this->render('teacher/modifyClass.html.twig', [
            'formModifyClass' => $formModifyClass->createView(),
            'students' => $students
        ]);

    }

















    /**
     * @Route("/teacher/inviteStudents/{classId}", name="inviteStudents")
     */
    public function inviteStudents($classId = null, Request $request, EntityManagerInterface $manager, PublisherInterface $publisher)
    {
        $class = $manager->getRepository('App:ClassGroup')->findOneById($classId);
        if ($class && $class->getOwner() == $this->getUser()) {
            $formInvitation = $this->createFormBuilder()
                ->add('students', HiddenType::class)
                ->add('sendInvitation', SubmitType::class)
                ->getForm();

            $formInvitation->handleRequest($request);
            if ($formInvitation->isSubmitted() && $formInvitation->isValid()) {


                $studentsIds = explode(',', $formInvitation->get('students')->getData());

                $studentsIds = array_filter($studentsIds, "ctype_digit");

                foreach ($studentsIds as $studentId) {
                    $student = $manager->getRepository('App:User')->findOneById($studentId);
                    if (($student) &&
                        !($manager->getRepository('App:RequestFromTeacher')->findOneBy([
                            'student' => $student->getId(),
                            'classGroup' => $classId
                        ]))) {
                        $invitation = new RequestFromTeacher();
                        $invitation->setStudent($student);
                        $invitation->setClassGroup($class);
                        $manager->persist($invitation);
                        $update = new Update('newRequest' . $student->getId(), "[]");
                        $publisher($update);


                    }
                    $manager->flush();
                }
                $this->addFlash('success', 'invitations sent successfully');
                return $this->redirect('/teacher/showClasses');

            }
            $students = $manager->getRepository('App:User')->findByRegisterAs('student');
            foreach ($students as $key => $student) {
                if ($student->getStudentClassGroups()->contains($class)) unset($students[$key]);
            }

            return $this->render('teacher/inviteStudents.html.twig', [
                'formInvitation' => $formInvitation->createView(),
                'students' => $students
            ]);

        }
        $this->addFlash('success', 'class not found');
        return $this->redirect('/teacher/showClasses');

    }


}