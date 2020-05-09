<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher", name="teacherHome")
     */
    public function main()
    {
//        dd($this->getUser());
        return $this->render("teacher/TeacherConnected.html.twig");
    }
}
