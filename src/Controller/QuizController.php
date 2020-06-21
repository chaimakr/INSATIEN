<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use App\Entity\QuizQuestion;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/teacher/addQuiz", name="addQuiz")
     */
    public function addQuiz( PublisherInterface $publisher,Request $request,EntityManagerInterface $manager)
    {
        $classes=$manager->getRepository('App:ClassGroup')->findByOwner($this->getUser()->getId());
        foreach ($classes as $key=>$class){
            $newKey=($key+1).'_'.$class->getTitle();
            $classes[$newKey]=$classes[$key];
            unset($classes[$key]);
        }

        $quiz=new Quiz();

        $formQuiz=$this->createFormBuilder($quiz)
            ->add('title')
            ->add('questionsAndAnswers',HiddenType::class,[
                'mapped'=>false
            ])
            ->add('class',ChoiceType::class,[
                'choices'=>$classes
            ])

            ->add('addQuiz',SubmitType::class)
            ->getForm();

        $formQuiz->handleRequest($request);

        if($formQuiz->isSubmitted() && $formQuiz->isValid() ){


            $questionsAndAnswers=json_decode($formQuiz->get('questionsAndAnswers')->getData());

            foreach ($questionsAndAnswers as $questionAndAnswers){
                $question=new QuizQuestion();
                $question->setContent($questionAndAnswers->question);
                foreach ($questionAndAnswers->answers as $answer){
                    $quizAnswer=new QuizAnswer();
                    $quizAnswer->setContent($answer->answer);
                    $quizAnswer->setValid($answer->valid);
                    $manager->persist($quizAnswer);
                    $question->addQuizAnswer($quizAnswer);
                }
                $quiz->addQuizQuestion($question);
                $manager->persist($question);


            }
            $manager->persist($quiz);
            $manager->flush();
            $this->addFlash('success','quiz addeed to class '.$quiz->getClass()->getTitle());
            return $this->redirectToRoute('TeacherShowClasses');


        }



//        $update = new Update('newQuiz','[]' );
//        $publisher($update);
        return $this->render("quiz/addQuiz.html.twig",[
            'formQuiz'=>$formQuiz->createView()
        ]);
    }

    /**
     * @Route("/student/joinQuiz/{quizId}", name="studentJoinQuiz")
     */
    public function studentJoinQuiz( PublisherInterface $publisher,$quizId,EntityManagerInterface $manager)
    {
        $quiz=$manager->getRepository('App:Quiz')->findOneById($quizId);

        if(!($quiz &&
        $quiz->getClass()->getStudentMembers()->contains($this->getUser()))
        ){
            return  new Response('cannot access quiz ', 401);

        }




        return $this->render("quiz/studentJoinQuiz.html.twig");
    }

    /**
     * @Route("/teacher/{classId}/allQuizzes", name="allQuizzes")
     */
    public function allQuizzes( EntityManagerInterface $manager,$classId)
    {
        $class=$manager->getRepository('App:ClassGroup')->findOneById($classId);
        if(!($class && $class->getOwner()==$this->getUser())){
            return  new Response('cannot access class ', 401);
        }

        $quizzes=$class->getQuizzes();
        return $this->render('quiz/allQuizzes.html.twig',[
            'quizes'=>$quizzes
        ]);



    }

    /**
     * @Route("/teacher/removeQuiz/{quizId}", name="removeQuiz")
     */
    public function removeQuiz( EntityManagerInterface $manager,$quizId)
    {
        $quiz=$manager->getRepository('App:Quiz')->findOneById($quizId);

        if(!($quiz && $quiz->getClass()->getOwner()==$this->getUser())){
            return  new Response('cannot access quiz ', 401);

        }

        $manager->remove($quiz);
        $manager->flush();

        return $this->redirectToRoute('allQuizzes',['classId'=>$quiz->getClass()->getId()]);

    }

    /**
     * @Route("/teacher/launchQuiz/{quizId}", name="launchQuiz")
     */
    public function launchQuiz( EntityManagerInterface $manager,$quizId)
    {
        $quiz=$manager->getRepository('App:Quiz')->findOneById($quizId);

        if(!($quiz && $quiz->getClass()->getOwner()==$this->getUser())){
            return  new Response('cannot access quiz ', 401);

        }


        return $this->render('quiz/teacherLaunchQuiz.html.twig',[
            'quiz'=>$quiz
        ]);

    }







}
