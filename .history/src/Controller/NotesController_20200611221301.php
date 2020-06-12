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



class NotesController extends AbstractController
{
    /**
     * @Route("/student/note", name="displayNotes",requirements={"noteIdgit addffffffffffffffffff"="\d+"})
     */
    public function allNotes(int $noteId = 1)
    {
        $manager = $this->getDoctrine()->getManager();
        $notes = $manager->getRepository('App:Note')->findByOwner($this->getUser()->getId());
        return $this->render('notes/note.html.twig', [
            "notes" => $notes
        ]);
    }
    /**
     * @Route("/student/note/{noteId}", name="displayNotes",requirements={"noteIdgit addffffffffffffffffff"="\d+"})
     */
    public function DisplayNote($noteId)
    {
        $manager = $this->getDoctrine()->getManager();
        $notes = $manager->getRepository('App:Note')->findByOwner($this->getUser()->getId());
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
        $form = $this->createFormBuilder($note)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('add', SubmitType::class ,[
                "attr"=>[
                    "class"=>"btn btn-info"
                ]
            ])
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($note);
            $manager->flush();
            $this->addFlash('success','a new note has been added ! ');

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



}
