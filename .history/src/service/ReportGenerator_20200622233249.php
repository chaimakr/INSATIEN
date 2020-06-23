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
use App\Entity\User;
use \stdClass;


    class ReportGenerator{
        public function report(Request $request, EntityManagerInterface $manager , User $user , \stdClass $obj ){
            $report = new Report();
        $Reportform = $this->createFormBuilder($report)
           
            ->add('ReportCause', ChoiceType::class, [
                "attr" => [
                    "label" => "Choose One:"
                ],
                "choices" => [
                    "one" => "Inapropriate content",
                    "two" => "Unsuitable behavior",
                    "three" => "Spam"
                ]

            ])
            ->add('Details', TextareaType::class, [
                "attr" => [
                    "placeholder" => "details.. ",
                ]
            ])
            ->add('send', SubmitType::class)
            ->getForm();
        $Reportform->handleRequest($request);

        if ($Reportform->isSubmitted() && $Reportform->isValid()) {
            $report->setDate(new \DateTime());
            $report->setReportedBy($user);
            $manager->persist($report);
            $manager->flush();
           
        }
        return $this->render('security/report.html.twig', [
            "form" => $Reportform->createView()
        ]);
    }


}    