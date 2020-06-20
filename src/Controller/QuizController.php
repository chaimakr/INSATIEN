<?php

namespace App\Controller;

use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/teacher/addQuiz", name="addQuiz")
     */
    public function addQuiz( PublisherInterface $publisher)
    {
        $quiz=new Quiz();
        $formQuiz=$this->createFormBuilder()
            ->add('title')
            ->add('questions',HiddenType::class,[
                'mapped'=>false
            ])
            ->add('answers',HiddenType::class,[
                'mapped'=>false
            ])
            ->add('addQuiz',SubmitType::class)
            ->getForm();


        ;

        $update = new Update('newQuiz','[]' );
        $publisher($update);
        return $this->render("quiz/addQuiz.html.twig",[
            'formQuiz'=>$formQuiz->createView()
        ]);
    }

    /**
     * @Route("/student/getQuiz", name="studentGetQuiz")
     */
    public function studentGetQuiz( PublisherInterface $publisher)
    {


        return $this->render("quiz/studentGetQuiz.html.twig");
    }


}
