<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Question;
use App\Entity\VoteQuestion;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Security;


class QuestionController extends AbstractController
{


public function canAccessClass($id){
    $class=$this->getDoctrine()->getManager()->getRepository('App:ClassGroup')->findOneById($id);
    if(in_array('ROLE_STUDENT',$this->getUser()->getRoles())){

        if(!($class && $class->getStudentsMembers()->contains($this->getUser()))){
            $this->addFlash("error",'cannot access class');
            return $this->redirect('/student/myClasses');
        };
    }
    elseif (in_array('ROLE_TEACHER',$this->getUser()->getRoles())){
        if(!($class && $class->getOwner()==$this->getUser())){
            $this->addFlash("error",'cannot access class');
            return $this->redirect('/teacher/showClasses');
        }
    }


}








    /**
     * @Route("/student/class/{id}/addQuestion", name="addQuestion")
     */
    public function add(Request $request, $id)
    {

        $test=$this->canAccessClass($id);
        if($test) return $test;
        $question = new Question();
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('App:User')->findOneById($this->getUser()->getId());
        $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        $question->setClass($class);
        $question->setOwner($user);
        $question->setEvaluation(0);
        $form = $this->createFormBuilder($question)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('add', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-primary"
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $question->setDate(new \DateTime());
            $manager->persist($question);
            $manager->flush();
            $this->addFlash('success', 'a new question has been added ! ');
            return $this->redirect('/student/class/' . $id . '/showMyQuestions');
        }
        return $this->render('question/addQuestion.html.twig', [
            'form' => $form->createView()]);
    }










    /**
     * @Route("/user/class/{id}/showAllQuestions", name="showAllQuestions")
     */
    public function allQuestions(Request $request, $id, PaginatorInterface $paginator)
    {

        $test=$this->canAccessClass($id);
        if($test) return $test;

        $manager = $this->getDoctrine()->getManager();
        $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        $donnees = $class->getQuestions();
        //dd($donnees);
        $questions = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('question/displayQuestion.html.twig', [
            'class' => $class,
            'questions' => $questions
        ]);
    }










    /**
     * @Route("/student/class/{id}/showMyQuestions", name="showMyQuestions")
     */
    public function MyQuestions(Request $request, $id, PaginatorInterface $paginator)
    {

        $test=$this->canAccessClass($id);
        if($test) return $test;

        $manager = $this->getDoctrine()->getManager();
        $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        $user = $this->getUser();
        $donnees = $manager->getRepository('App:Question')->findMyQuestionInSpecificClass($id, $user->getId());

        $questions = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('question/displayQuestion.html.twig', [
            'class' => $class,
            'questions' => $questions
        ]);
    }


 /**
     * @Route("/student/class/{id}/deleteMyQuestion/{idQ}", name="showMyQuestions")
     */
    public function DeleteMyQuestions(Request $request, $id, PaginatorInterface $paginator)
    {

        $test=$this->canAccessClass($id);
        if($test) return $test;

        $manager = $this->getDoctrine()->getManager();
        $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        $user = $this->getUser();
        $donnees = $manager->getRepository('App:Question')->findOneById($idQ);

        return $this->redirect('/student/class/{'.$id.'}/showMyQuestions');
    }







    /**
     * @Route("/user/class/{id}/searchQuestions", name="SearchQuestions" , methods={"GET","POST"})
     */
    public function SearchQuestions(Request $request, $id, PaginatorInterface $paginator)
    {
        $test=$this->canAccessClass($id);
        if($test) return $test;


        $query = $request->request->get('search');
        $manager = $this->getDoctrine()->getManager();
        $data = $manager->getRepository('App:Question')->findAll();
        foreach ($data as $key => $d) {
            if ((strpos($d->getTitle(), $query) === false) && (strpos($d->getContent(), $query)) === false) {
                unset($data[$key]);
            }
        }
        $questions = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            5
        );
        $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        return $this->render('question/displayQuestion.html.twig', [
            'class' => $class,
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/user/question/{action}/{id}", name="voteQuestion")
     */
    public function voteQuestion(Request $request, $id, $action,EntityManagerInterface $manager)
    {
        $question=$manager->getRepository('App:Question')->findOneById($id);

        if($question && in_array($action,['up','down'])){
            $test=$this->canAccessClass($question->getClass()->getId());
            if($test) return $test;

            $votes=$manager->getRepository('App:VoteQuestion')->findBy([
                'question'=>$question->getId(),
                'user'=>$this->getUser()
            ]);

            $sommeVotes=0;
            foreach ($votes as $vote){
                if($vote->getValue()) $sommeVotes++;
                else $sommeVotes--;
            }


            if($action=='up' && $sommeVotes==1){
                $this->addFlash('error','cannot double upVote');

                return $this->redirect('/user/class/'.$question->getClass()->getId().'/showAllQuestions#question'.$id);

            }
            elseif ($action=='down' && $sommeVotes==-1){
                $this->addFlash('error','cannot double downVote');

                return $this->redirect('/user/class/'.$question->getClass()->getId().'/showAllQuestions#question'.$id);

            }


            $vote=new VoteQuestion();
            $vote->setUser($this->getUser());
            $vote->setQuestion($question);
            if($action=='up'){
                $question->setEvaluation($question->getEvaluation()+1);
                $vote->setValue(true);
            }elseif ($action=='down'){
                $question->setEvaluation($question->getEvaluation()-1);
                $vote->setValue(false);
            }
            $manager->persist($question);
            $manager->persist($vote);
            $manager->flush();

            $this->addFlash('success','vote registred');

            return $this->redirect('/user/class/'.$question->getClass()->getId().'/showAllQuestions#question'.$id);


        }



    }


}
