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
 * @Route("/student/class/{id}" , name="")
 */

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="question")
     */
    public function index(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        if (($request->get('id'))) 
            $class = $manager->getRepository('App:ClassGroup')->findOneById($request->get('id'));
            else{
                $this->addFlash('error','class not found');
            }
        return $this->render('question/index.html.twig',[
            'class' => $class
        ]);
    }

        /**
     * @Route("/addQuestion", name="addQuestion")
     */
    public function add(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        if (($request->get('id'))) 
            $class = $manager->getRepository('App:ClassGroup')->findOneById($request->get('id'));
            else{
                $this->addFlash('error','class not found');
            }
        return $this->render('addQuestion/index.html.twig');
    }

    
}
