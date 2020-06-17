<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Question;
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


/**
 * @Route("/student/class/{id}")
 */
class QuestionController extends AbstractController
{


public function checkAccess($id){

    $class=$this->getDoctrine()->getManager()->getRepository('App:ClassGroup')->findOneById($id);
    if(!($class && $class->getStudentsMembers()->contains($this->getUser()))){
      $this->addFlash("error",'cannot access class');
        return $this->redirect('/student/myClasses');
    };
}








    /**
     * @Route("/addQuestion", name="addQuestion")
     */
    public function add(Request $request, $id)
    {
        $test=$this->checkAccess($id);
        if($test) return $test;
        $question = new Question();
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('App:User')->findOneById($this->getUser()->getId());
        $class = $manager->getRepository('App:ClassGroup')->findOneById($id);
        $question->setClass($class);
        $question->setOwner($user);
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
     * @Route("/showAllQuestions", name="showAllQuestions")
     */
    public function allQuestions(Request $request, $id, PaginatorInterface $paginator)
    {
        $test=$this->checkAccess($id);
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
     * @Route("/showMyQuestions", name="showMyQuestions")
     */
    public function MyQuestions(Request $request, $id, PaginatorInterface $paginator)
    {
        $test=$this->checkAccess($id);
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
     * @Route("/searchQuestions", name="SearchQuestions" , methods={"GET","POST"})
     */
    public function SearchQuestions(Request $request, $id, PaginatorInterface $paginator)
    {
        $test=$this->checkAccess($id);
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


}
