<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;


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
     * @Route("/addQuestion", name="add")
     */
    public function add()
    {
        return $this->render('question/index.html.twig');
    }

    
}
