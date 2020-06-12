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
            if(in_array($this->getUser(),(array)$class->getStudentsMembers())){
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
    public function joinClass($id=null,EntityManagerInterface $manager)
    {
        $class=$manager->getRepository('App:ClassGroup')->findOneById($id);

        if($class){

            $allRequests=$manager->getRepository('App:Request')->findAll();
            foreach ($allRequests as $request){
                if($request->getStudent()==$this->getUser()&& $request->getClass()==$class){
                    $this->addFlash('info','You already sent a request to join '.$class->getTitle() );
                    return $this->redirect('/student/showClasses');
                }
            }

            $requestJoin=new \App\Entity\Request();
            $requestJoin->setStudent($this->getUser());
            $requestJoin->setClass($class);
            $manager->persist($requestJoin);
            $manager->flush();
            $this->addFlash('success','Your request to join '.$class->getTitle().' has been send successfully.' );
            return $this->redirect('/student/showClasses');
        }
        else{
            $this->addFlash('error','Class not found .' );
            return $this->redirect('/student/showClasses');

        }


    }


}