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


class NotesController extends AbstractController
{
    /**
     * @Route("/student/note", name="note")
     */
    public function note()
    {
        return $this->render('notes/note.html.twig');
    }
    /**
     * @Route("/student/note/add", name="addNote")
     */
    public function note()
    {
        return $this->render('notes/note.html.twig');
    }

}
