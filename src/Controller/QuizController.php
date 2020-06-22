<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use App\Entity\QuizQuestion;
use App\Entity\QuizSession;
use App\Entity\QuizTry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/teacher/addQuiz", name="addQuiz")
     */
    public function addQuiz(PublisherInterface $publisher, Request $request, EntityManagerInterface $manager)
    {
        $classes = $manager->getRepository('App:ClassGroup')->findByOwner($this->getUser()->getId());
        foreach ($classes as $key => $class) {
            $newKey = ($key + 1) . '_' . $class->getTitle();
            $classes[$newKey] = $classes[$key];
            unset($classes[$key]);
        }

        $quiz = new Quiz();

        $formQuiz = $this->createFormBuilder($quiz)
            ->add('title')
            ->add('questionsAndAnswers', HiddenType::class, [
                'mapped' => false
            ])
            ->add('class', ChoiceType::class, [
                'choices' => $classes
            ])
            ->add('addQuiz', SubmitType::class)
            ->getForm();

        $formQuiz->handleRequest($request);

        if ($formQuiz->isSubmitted() && $formQuiz->isValid()) {


            $questionsAndAnswers = json_decode($formQuiz->get('questionsAndAnswers')->getData());

            foreach ($questionsAndAnswers as $questionAndAnswers) {
                $question = new QuizQuestion();
                $question->setContent($questionAndAnswers->question);
                foreach ($questionAndAnswers->answers as $answer) {
                    $quizAnswer = new QuizAnswer();
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
            $this->addFlash('success', 'quiz addeed to class ' . $quiz->getClass()->getTitle());
            return $this->redirectToRoute('TeacherShowClasses');


        }


//        $update = new Update('newQuiz','[]' );
//        $publisher($update);
        return $this->render("quiz/addQuiz.html.twig", [
            'formQuiz' => $formQuiz->createView()
        ]);
    }










    /**
     * @Route("/student/joinQuiz/{quizSessionId}", name="studentJoinQuiz")
     */
    public function studentJoinQuiz(PublisherInterface $publisher, $quizSessionId, EntityManagerInterface $manager)
    {
        $quizSession = $manager->getRepository('App:QuizSession')->findOneById($quizSessionId);

        if (!($quizSession &&
            $quizSession->getQuiz()->getClass()->getStudentsMembers()->contains($this->getUser()))
        ) {
            return new Response('cannot access quiz ', 401);

        }

//        $update = new Update('joinedQuiz'.$quizSessionId,json_encode([$this->getUser()->getId(),$this->getUser()->getFirstName(),$this->getUser()->getLastName()]) );
//        $publisher($update);


        return $this->render("quiz/studentJoinQuiz.html.twig", [
            'quizSession' => $quizSession
        ]);
    }










    /**
     * @Route("/teacher/{classId}/allQuizzes", name="allQuizzes")
     */
    public function allQuizzes(EntityManagerInterface $manager, $classId)
    {
        $class = $manager->getRepository('App:ClassGroup')->findOneById($classId);
        if (!($class && $class->getOwner() == $this->getUser())) {
            return new Response('cannot access class ', 401);
        }

        $quizzes = $class->getQuizzes();
        return $this->render('quiz/allQuizzes.html.twig', [
            'quizes' => $quizzes
        ]);



    }










    /**
     * @Route("/teacher/removeQuiz/{quizId}", name="removeQuiz")
     */
    public function removeQuiz(EntityManagerInterface $manager, $quizId)
    {
        $quiz = $manager->getRepository('App:Quiz')->findOneById($quizId);

        if (!($quiz && $quiz->getClass()->getOwner() == $this->getUser())) {
            return new Response('cannot access quiz ', 401);

        }

        $manager->remove($quiz);
        $manager->flush();

        return $this->redirectToRoute('allQuizzes', ['classId' => $quiz->getClass()->getId()]);

    }










    /**
     * @Route("/teacher/launchQuiz/{quizId}", name="launchQuiz")
     */
    public function launchQuiz(EntityManagerInterface $manager, $quizId, Request $request)
    {
        $session = $request->getSession();

        $sessionQuiz = $session->get('quizSession');
        $quizSession = $manager->getRepository('App:QuizSession')->findOneById($sessionQuiz);
        if ($quizSession && $quizSession->getQuiz()->getClass()->getOwner() == $this->getUser()) {
            if ($quizSession->getStatus() == -1) {
                $session->remove('quizSession');
                return $this->render('quiz/teacherQuizResults.html.twig');
            }
            return $this->render('quiz/teacherLaunchQuiz.html.twig', [
                'quizSession' => $quizSession
            ]);
        }





        $quiz = $manager->getRepository('App:Quiz')->findOneById($quizId);


        if (!($quiz && $quiz->getClass()->getOwner() == $this->getUser())) {

            return new Response('cannot access quiz ', 401);

        }

        $quizSession = new QuizSession();
        $quizSession->setQuiz($quiz);
        $quizSession->setStatus(0);
        $manager->persist($quizSession);
        $manager->flush();
        $session->set('quizSession', $quizSession->getId());


        return $this->render('quiz/teacherLaunchQuiz.html.twig', [
            'quizSession' => $quizSession
        ]);

    }










    /**
     * @Route("/teacher/quizState/{quizSessionId}/{quizState}", name="changeQuizState")
     */
    public function changeQuizState(PublisherInterface $publisher, $quizSessionId, $quizState, EntityManagerInterface $manager, Request $request)
    {
        $quizSession = $manager->getRepository('App:QuizSession')->findOneById($quizSessionId);


        if (!($quizSession && $quizSession->getQuiz()->getClass()->getOwner() == $this->getUser())) {
            return new Response('cannot access quiz ', 401);

        }

        if ($quizSession->getStatus() == -1) {
            $request->getSession()->remove('quizSession');
            return $this->render('quiz/teacherQuizResults.html.twig');
        }


        if ($quizState > $quizSession->getQuiz()->getQuizQuestions()->count()) $quizSession->setStatus(-1);
        else $quizSession->setStatus($quizState);


        $manager->persist($quizSession);
        $manager->flush();

        if ($quizState <= $quizSession->getQuiz()->getQuizQuestions()->count()) {
            $answers = [];
            $question = $quizSession->getQuiz()->getQuizQuestions()[$quizState - 1];

            foreach ($question->getQuizAnswers() as $answer) {
                array_push($answers, $answer->getContent());
            }


            $update = new Update('quizState' . $quizSessionId, json_encode(['question' => $question->getContent(), 'answers' => $answers]));
            $publisher($update);

        } elseif ($quizState > $quizSession->getQuiz()->getQuizQuestions()->count()) {
            $update = new Update('quizState' . $quizSessionId, 'quizEnded');
            $publisher($update);
        }

        return new Response('updated');



    }










    /**
     * @Route("/student/quizAnswer/{quizSessionId}/{quizState}/{answerId}", name="addAnswerToQuiz")
     */
    public function addAnswerToQuiz(PublisherInterface $publisher, $quizSessionId, $quizState, $answerId, EntityManagerInterface $manager)
    {
        $quizSession = $manager->getRepository('App:QuizSession')->findOneById($quizSessionId);

        if (!($quizSession &&
            $quizSession->getQuiz()->getClass()->getStudentsMembers()->contains($this->getUser()))
        ) {
            return new Response('cannot access quiz ', 401);

        }



        $quizTry = $manager->getRepository('App:QuizTry')->findOneBy([
            'student' => $this->getUser(),
            'quizSession' => $quizSession
        ]);

        if($quizSession->getStatus()==-1 || $quizSession->getStatus()!=$quizState){
            return new Response('cannot access quiz', 401);
        }


        if (!$quizTry) {
            $quizTry = new QuizTry();
        }

        $quizTry->setStudent($this->getUser());
        $quizTry->setQuizSession($quizSession);

        $newAnswer = $quizSession->getQuiz()->getQuizQuestions()[$quizState - 1]->getQuizAnswers()[$answerId];

        foreach ($quizTry->getQuizAnswers() as $answer) {
            if ($quizSession->getQuiz()->getQuizQuestions()[$quizState - 1]->getQuizAnswers()->contains($answer))
                $quizTry->removeQuizAnswer($answer);
        }

        $quizTry->addQuizAnswer($newAnswer);

        $manager->persist($quizTry);
        $manager->flush();

        return new Response('saved');



    }


}
