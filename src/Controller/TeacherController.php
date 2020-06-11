<?php

namespace App\Controller;

use App\Entity\ClassGroup;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher", name="teacherHome")
     */
    public function main()
    {



        return $this->render("teacher/TeacherConnected.html.twig");
    }










    /**
     * @Route("/teacher/addClass", name="addClass")
     */
    public function addClass(Request $request, EntityManagerInterface $manager, \Swift_Mailer $mailer)
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
                    $invitation = (new \Swift_Message('invitation'))
                        ->setFrom('insatien.help@gmail.com')
                        ->setTo($student->getEmail())
                        ->setBody(
                            'invitation to class',
                            'text/html'
                        );
                    $mailer->send($invitation);
                }
            }

            $manager->persist($class);
            $manager->flush();


            return $this->redirect('/teacher/addClass');

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
    public function modifyClass(Request $request,EntityManagerInterface $manager, $classId = null)
    {

        $class = $manager->getRepository('App:ClassGroup')->findOneById($classId);


        if (!($class && $class->getOwner() == $this->getUser())) {
            $this->addFlash("error","you don't own this class");
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

        $students=$manager->getRepository('App:ClassGroup')->findOneById($classId)->getStudentsMembers();

        if($formModifyClass->isSubmitted()&& $formModifyClass->isValid()){


            foreach ($students as $student){
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


}