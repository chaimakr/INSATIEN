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
//        dd($this->getUser());
        return $this->render("student/StudentConnected.html.twig");
    }










    /**
     * @Route("/student/showClasses", name="studentShowClasses")
     */
    public function showClasses(EntityManagerInterface $manager)
    {
        $otherClasses=$manager->getRepository('App:ClassGroup')->findAll();

        foreach ($otherClasses as $key=>$class){

            if($class->getStudentsMembers()->contains($this->getUser())){
                unset($otherClasses[$key]);
            }
        }



        return $this->render("student/showClasses.html.twig",[
            'classes'=>$otherClasses
        ]);
    }


    /**
     * @Route("/student/joinClass/{id}", name="joinClass")
     */
    public function joinClass($id=null,EntityManagerInterface $manager,PublisherInterface $publisher)
    {
        $class=$manager->getRepository('App:ClassGroup')->findOneById($id);

        if(($class) && !($class->getStudentsMembers()->contains($this->getUser()))){


            $allRequests=$manager->getRepository('App:RequestFromStudent')->findAll();
            foreach ($allRequests as $request){
                if($request->getStudent()==$this->getUser()&& $request->getClassGroup()==$class){
                    $this->addFlash('info','You already sent a request to join '.$class->getTitle() );
                    return $this->redirect('/student/showClasses');
                }
            }

            $requestJoin=new \App\Entity\RequestFromStudent();
            $requestJoin->setStudent($this->getUser());
            $requestJoin->setClassGroup($class);
            $manager->persist($requestJoin);
            $manager->flush();
            $update = new Update('newRequest'.$class->getOwner()->getId(),"[]");
            $publisher($update);
            $this->addFlash('success','Your request to join '.$class->getTitle().' has been send successfully.' );
            return $this->redirect('/student/showClasses');
        }
        else{
            $this->addFlash('error','Class not found .' );
            return $this->redirect('/student/showClasses');

        }


    }


    /**
     * @Route("/student/myClasses", name="myClasses")
     */
    public function myClasses(EntityManagerInterface $manager)
    {

        $classes=$manager->getRepository('App:User')->findOneById($this->getUser()->getId())->getStudentClassGroups();




        return $this->render("student/myClasses.html.twig",[
            'classes'=>$classes
        ]);
    }




    /**
     * @Route("/student/request/{action}/{id}", name="StudentManageRequests")
     */
    public function manageRequests(EntityManagerInterface $manager,$id,$action)
    {
        $request = $manager->getRepository('App:RequestFromTeacher')->findOneById($id);


        if($request &&$request->getStudent()==$this->getUser() && in_array($action,['accept','deny'])){
            if($action=='accept'){
                $class=$request->getClassGroup();
                $class->addStudentsMember($request->getStudent());
                $manager->persist($class);
            }
            $manager->remove($request);
            $manager->flush();

            if($action=='deny')
                $this->addFlash('info','request removed !');
            else
                $this->addFlash('info','request accepted !');

            return $this->redirect('/student/showRequests');

        }

        $this->addFlash('error','connot access request ');
        return $this->redirect('/student/showRequests');

    }


}