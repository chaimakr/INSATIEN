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
 * @Route("/student/class")
 */

class QuestionController extends AbstractController
{
    
    
     /**
     * @Route("/addQuestion", name="addQuestion")
     */
    public function add(Request $request)
    {
        return $this->render('question/addQuestion.html.twig');
    }
    
    
    /**
     * @Route("/{id}/", name="question")
     */
    public function index(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        
            $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        return $this->render('question/index.html.twig',[
            'class' => $class
        ]);
    }


    
}
