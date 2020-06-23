<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


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













































}