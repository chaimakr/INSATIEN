<?php
namespace App\Service;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Report;

    class ReportGenerator{
        public function report(){
            $report = new Report();
        $Reportform = $this->createFormBuilder($message)
           
            ->add('Subject', ChoiceType::class, [
                "attr" => [
                    "label" => "Choose One:"
                ],
                "choices" => [
                    "service" => "General Customer Service",
                    "Suggestions" => "Suggestions",
                    "Feedbacks" => "Feedbacks"
                ]

            ])
            ->add('Message', TextareaType::class, [
                "attr" => [
                    "placeholder" => "Message ",
                ]
            ])
            ->add('send', SubmitType::class)
            ->add('reset', ResetType::class)
            ->getForm();
        $mailform->handleRequest($request);

        if ($mailform->isSubmitted() && $mailform->isValid()) {
            $manager->persist($message);
            $manager->flush();
            $contact = $mailform->getData();
        }
    }


}    