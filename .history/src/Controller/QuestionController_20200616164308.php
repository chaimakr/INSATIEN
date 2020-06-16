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
     * @Route("/addQuestion", name="addQuestion")
     */
    public function add(Request $request, $id)
    {
        $question = new Question();
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('App:User')->findOneById($this->getUser()->getId());
        $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        $question->setClass($class);
        $question->setOwner($user);
        $form = $this->createFormBuilder($question)
        ->add('title', TextType::class)
        ->add('content', TextareaType::class)
        ->add('add', SubmitType::class,[
            "attr"=>[
                "class"=>"btn btn-primary"
            ]
        ])
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $question->setDate(new \DateTime());
            $manager->persist($question);
            $manager->flush();
            $this->addFlash('success','a new question has been added ! ');
            return $this->redirect('/student/class/'.$id.'/showClass');
        }
        return $this->render('question/addQuestion.html.twig', [
            'form' => $form->createView()]);
    }
    
    
    /**
     * @Route("/showClass", name="question")
     */
    public function index(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        
            $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        return $this->render('question/index.html.twig',[
            'class' => $class
        ]);
    }

    /**
     * @Route("/showAllQuestions", name="question")
     */
    public function allQuestions(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        $questions=$class->getResponses()

        return $this->render('question/displayQuestion.html.twig',[
            'class' => $class
        ]);
    }



    
}
