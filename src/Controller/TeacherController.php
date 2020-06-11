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
    public function addClass(Request $request,EntityManagerInterface $manager,\Swift_Mailer $mailer)
    {


        $class=new ClassGroup();
        $class->setOwner($this->getUser());

        $modifyId=$request->get('modifyId');

        if($modifyId){

            $tempClass=$manager->getRepository('App:ClassGroup')->findOneById($modifyId);
            if($tempClass->getOwner()==$this->getUser()){
                $class=$tempClass;
            }
            else{
                $this->addFlash('you don\' t own this class');
            }
        }

        $formAddClass=$this->createFormBuilder($class)
            ->add('title')
            ->add('description')
            ->add('students',HiddenType::class,[
                'mapped'=>false,
                'required'=>false
            ])
            ->add('submit',SubmitType::class)

            ->getForm()
            ;

        $formAddClass->handleRequest($request);
        if($formAddClass->isSubmitted() && $formAddClass->isValid()){

            $studentsIds=explode(',',$formAddClass->get('students')->getData());

            $studentsIds=array_filter($studentsIds,"ctype_digit");

            foreach ($studentsIds as $studentId){
                $student=$manager->getRepository('App:User')->findOneById($studentId);
                if($student){
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

        return $this->render("teacher/addClass.html.twig",[
            'formAddClass'=>$formAddClass->createView(),
            'students'=>$manager->getRepository('App:User')->findByRegisterAs("student")
        ]);
    }
}