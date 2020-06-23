<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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





}