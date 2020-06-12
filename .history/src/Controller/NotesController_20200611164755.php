<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{
    /**
     * @Route("/student/note", name="note")
     */
    public function note()
    {
        $manager = $this->getDoctrine()->getManager();        
        $user = $manager->getRepository('App:User')->findOneById($this->getUser()->getId());
        return $this->render("student/note.html.twig");
    }
}
