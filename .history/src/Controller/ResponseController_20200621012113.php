<?php

namespace App\Controller;

use App\Entity\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResponseController extends AbstractController
{
    public function canAccessClass($id)
    {
        $class = $this->getDoctrine()->getManager()->getRepository('App:ClassGroup')->findOneById($id);
        if (in_array('ROLE_STUDENT', $this->getUser()->getRoles())) {

            if (!($class && $class->getStudentsMembers()->contains($this->getUser()))) {
                $this->addFlash("error", 'cannot access class');
                return $this->redirect('/student/myClasses');
            };
        } elseif (in_array('ROLE_TEACHER', $this->getUser()->getRoles())) {
            if (!($class && $class->getOwner() == $this->getUser())) {
                $this->addFlash("error", 'cannot access class');
                return $this->redirect('/teacher/showClasses');
            }
        }


    }










    /**
     * @Route("/user/questionResponces/{id}", name="allResponces")
     */
    public function allResponse(EntityManagerInterface $manager, $id,Request $request)
    {


        $question = $manager->getRepository('App:Question')->findOneById($id);

        if($question){
            $test=$this->canAccessClass($question->getClass()->getId());
            if($test) return $test;

            $allResponses=$question->getResponses();

            $response=new Response();
            if (($request->get('ModifyId'))) 
            $response = $manager->getRepository('App:Response')->findOneById($request->get('ModifyId'));
            $formAddResponse=$this->createFormBuilder($response)
                ->add('content',TextareaType::class)
                ->add('addResponse',SubmitType::class)
                ->getForm();

            $formAddResponse->handleRequest($request);

            if($formAddResponse->isSubmitted()&& $formAddResponse->isValid()){

                $response->setDate(new \DateTime());
                $response->setQuestion($question);
                $response->setOwner($this->getUser());
                $manager->persist($response);
                $manager->flush();

                return $this->redirect('/user/questionResponces/'.$id);

            }

            
            $reply=new Response();
            $formAddReplyToResponse=$this->createFormBuilder($reply)
            ->add('content',TextareaType::class)
            ->add('addReply',SubmitType::class)
            ->getForm();

        $formAddReplyToResponse->handleRequest($request);

        if($formAddReplyToResponse->isSubmitted()&& $formAddReplyToResponse->isValid()){
            $responseConcerned=$manager->getRepository('App:Response')->findOneById($request->request->get("ResponseId"));
            $reply->setDate(new \DateTime());
            $reply->setmain($responseConcerned);
            $reply->setOwner($this->getUser());
            $manager->persist($reply);
            $manager->flush();
            return $this->redirect('/user/questionResponces/'.$id);

        }

            return $this->render('user/questionResponses.html.twig',[
                'question'=>$question,
                'allResponses'=>$allResponses,
                'formAddResponse'=>$formAddResponse->createView(),
                'form'=>$formAddReplyToResponse
            ]);
        }

        return $this->redirect('/');



    }
    /**
     * @Route("/user/Response/delete/{id}", name="deleteResponse")
     */
    public function deleteResponse($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $response = $manager->getRepository('App:Response')->findOneById($id);
        $questionId=$response->getQuestion()->getId();
        if ($response->getOwner()->getId() != $this->getUser()->getId())
            $this->addFlash('error', "deletion failed !!");

        else {
            $manager->remove($response);
            $manager->flush();
            $this->addFlash('success', "Response has been deleted !");
        }

        return $this->redirect('/user/questionResponces/'.$questionId);
    }

    /**
     * @Route("/user/Reply/delete/{replyId}", name="deleteResponse")
     */
    public function deleteReply($replyId)
    {
        $manager = $this->getDoctrine()->getManager();
        $reply = $manager->getRepository('App:Response')->findOneById($replyId);
        $questionId=$reply->getMain()->getQuestion()->getId();
        if ($reply->getOwner()->getId() != $this->getUser()->getId())
            $this->addFlash('error', "deletion failed !!");

        else {
            $manager->remove($reply);
            $manager->flush();
            $this->addFlash('success', "Reply has been deleted !");
        }

        return $this->redirect('/user/questionResponces/'.$questionId);


    }
}
