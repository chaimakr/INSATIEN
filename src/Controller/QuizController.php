<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use App\Entity\QuizQuestion;
use App\Entity\QuizTry;
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
        $quiz->getClass()->getStudentsMembers()->contains($this->getUser()))
        ){
            return  new Response('cannot access quiz ', 401);

        }

        $update = new Update('joinedQuiz'.$quizId,json_encode([$this->getUser()->getId(),$this->getUser()->getFirstName(),$this->getUser()->getLastName()]) );
        $publisher($update);


        return $this->render("quiz/studentJoinQuiz.html.twig",[
            'quiz'=>$quiz
        ]);
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


//        dd(json_encode($quiz));
        return $this->render('quiz/teacherLaunchQuiz.html.twig',[
            'quiz'=>$quiz
        ]);

    }

    /**
     * @Route("/student/quitQuiz/{quizId}", name="studentQuitQuiz")
     */
    public function studentQuitQuiz( PublisherInterface $publisher,$quizId)
    {

        $update = new Update('quitQuiz'.$quizId,$this->getUser()->getId() );
        $publisher($update);

        return new Response('quit');
    }


    /**
     * @Route("/teacher/quizState/{quizId}/{quizState}", name="changeQuizState")
     */
    public function changeQuizState( PublisherInterface $publisher,$quizId,$quizState)
    {
        $quiz=$this->getDoctrine()->getManager()->getRepository('App:Quiz')->findOneById($quizId);

        if(!($quiz && $quiz->getClass()->getOwner()==$this->getUser())){
            return  new Response('cannot access quiz ', 401);

        }
        if($quizState<=$quiz->getQuizQuestions()->count()){
            $answers=[];
            $question=$quiz->getQuizQuestions()[$quizState-1];

            foreach ($question->getQuizAnswers() as $answer){
                array_push($answers,$answer->getContent());
            }


            $update = new Update('quizState'.$quizId,json_encode( ['question'=>$question->getContent(),'answers'=>$answers])) ;
            $publisher($update);

        }
        elseif($quizState=$quiz->getQuizQuestions()->count()+1){
            $update = new Update('quizState'.$quizId,'quizEnded') ;
            $publisher($update);
        }

        return new Response('updated');



    }

    /**
     * @Route("/student/quizAnswer/{quizId}/{quizState}/{answerId}", name="addAnswerToQuiz")
     */
    public function addAnswerToQuiz( PublisherInterface $publisher,$quizId,$quizState,$answerId,EntityManagerInterface $manager)
    {
        $quiz=$manager->getRepository('App:Quiz')->findOneById($quizId);

        if(!($quiz &&
            $quiz->getClass()->getStudentsMembers()->contains($this->getUser()))
        ){
            return  new Response('cannot access quiz ', 401);

        }
        $quizTry=$manager->getRepository('App:QuizTry')->findOneBy([
            'student'=>$this->getUser(),
            'quiz'=>$quiz
        ]);
        if(!$quizTry){
            $quizTry=new QuizTry();
        }

        $quizTry->setStudent($this->getUser());
        $quizTry->setQuiz($quiz);

        $newAnswer=$quiz->getQuizQuestions()[$quizState-1]->getQuizAnswers()[$answerId];

        foreach ($quizTry->getQuizAnswers() as $answer){
            if($quiz->getQuizQuestions()[$quizState-1]->getQuizAnswers()->contains($answer))
            $quizTry->removeQuizAnswer($answer);
        }

        $quizTry->addQuizAnswer($newAnswer);

        $manager->persist($quizTry);
        $manager->flush();

        return new Response('saved');



    }






}
