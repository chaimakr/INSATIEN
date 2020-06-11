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
     * @Route("/student/note", name="displayNotes")
     */
    public function note()
    {
        return $this->render('notes/note.html.twig');
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
            ->add('add', SubmitType::class)
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($covoiturage);
            $manager->flush();
            $this->addFlash('success','a new note has been added');

        }
        
        return $this->render('notes/note.html.twig', [
            'form' => $form->createView()]
        );

        return $this->render('notes/note.html.twig');
    }

}
