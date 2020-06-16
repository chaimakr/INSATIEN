<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/student/class/{id}")
 */

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="question")
     */
    public function index(Request $request)
    {
        return $this->render('question/index.html.twig');
    }

        /**
     * @Route("/addQuestion", name="question")
     */
    public function add()
    {
        return $this->render('question/index.html.twig');
    }

    
}
