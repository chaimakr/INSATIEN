<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\ClassGroup;

class ClassController extends AbstractController
{
    /**
     * @Route("/createClass", name="createClass")
     */
    public function create(Request $request)
    {
        $class = new ClassGroup();
        $class->setOwner($this->getuser());
        $form = $this->createFormBuilder($class)
        ->add('title', TypeText::class , [
            "attr" => [
                "class" => "form-control mb-4",
                "placeholder" => "title"
            ]
        ])
        ->add('add', SubmitType::class)
        ->getForm();
        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            echo "failed : " . $e->getMessage();
        }
        $manager->persist($compte);
        $manager->flush();
        return $this->render('class/create.html.twig', [
            "form" => $form->createView()
        ]);
        
    }
}
