<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;



class NotesController extends AbstractController
{
    /**
     * @Route("/student/note", name="displayNotes"))
     */
    public function allNotes(Request $request, PaginatorInterface $paginator)
    {
        $manager = $this->getDoctrine()->getManager();
        $donnees = $manager->getRepository('App:Note')->findByOwner($this->getUser()->getId());
        $notes = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1),
            5 
        );
        return $this->render('notes/note.html.twig', [
            "notes" => $notes
        ]);
    }
    
    /**
     * @Route("/student/note/{noteId}", name="displayNote",requirements={"noteId"="\d+"})
     */
     public function DisplayNote($noteId , Request $request, PaginatorInterface $paginator)
    {
        $manager = $this->getDoctrine()->getManager();
        $donnees = $manager->getRepository('App:Note')->findByOwner($this->getUser()->getId());
        $notes = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1),
            5 
        );
        $NOTE = $manager->getRepository('App:Note')->findOneById($noteId);
        return $this->render('notes/note.html.twig', [
            "notes" => $notes,
            "NOTE" => $NOTE
        ]);
    }
    
    
    /**
     * @Route("/student/note/add", name="addNote")
     */
    public function addNote(Request $request)
    {
        $note = new note();
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('App:User')->findOneById($this->getUser()->getId());
        $note->setOwner($user);
        if (($request->get('id'))) 
            $note = $manager->getRepository('App:Note')->findOneById($request->get('id'));
        $form = $this->createFormBuilder($note)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class);
            if (($request->get('id'))){
            $form=$form->add('modify', SubmitType::class,[
                "attr"=>[
                    "class"=>"btn btn-info"
                ]
            ])->getForm();}
        else
            $form=$form->add('add', SubmitType::class,[
                "attr"=>[
                    "class"=>"btn btn-info"
                ]
            ])->getForm();
            

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note->serDateTime(DATE_ADD(CURRENT_DATE());
            $manager->persist($note);
            $manager->flush();
            if (($request->get('id'))) 
            $this->addFlash('success','note updated ! ');
            else
            $this->addFlash('success','a new note has been added ! ');
            return $this->redirect('/student/note');
        }
        
        return $this->render('notes/note.html.twig', [
            'form' => $form->createView()]
        );

        return $this->render('notes/note.html.twig');
    }
    
    /**
     * @Route("/student/note/delete/{noteId}", name="deleteNote")
     */
    public function deleteNote($noteId)
    {
        $manager = $this->getDoctrine()->getManager();
        $note = $manager->getRepository('App:Note')->findOneById($noteId);
        if ($note->getOwner()->getId() != $this->getUser()->getId())
            $this->addFlash('error', "deletion failed !!");

        else {
            $manager->remove($note);
            $manager->flush();
            $this->addFlash('success', "note has been deleted !");
        }

        return $this->redirect('/student/note');


    }

    /**
     * @Route ("/note/Search", name="Search", methods={"GET","POST"})
     */
       
    public function search(Request $request)
    {
        if(isset($_POST["recherche"])){
            $em = $this->container->get('doctrine')->getManager();
            $notes = $em->getRepository('App\Entity\Note')->search($_POST["recherche"]);
            return $this->render('note/note.html.twig', [
                'notes' => $notes
            ]); 
        }
         
    }


}
