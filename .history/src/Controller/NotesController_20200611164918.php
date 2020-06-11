<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{
    /**
     * @Route("/student/note", name="note")
     */
    public function note()
    {
        
    }
}
