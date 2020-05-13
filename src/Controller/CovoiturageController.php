<?php

namespace App\Controller;

use App\Entity\Covoiturage;
use App\Entity\MapPoint;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CovoiturageController extends AbstractController
{
    /**
     * @Route("/covoiturage", name="homeCovoiturage")
     */
    public function index()
    {
        return $this->render('covoiturage/index.html.twig', [
            'controller_name' => 'CovoiturageController',
        ]);
    }

    /**
     * @Route("/covoiturage/add", name="AddCovoiturage")
     */
        public function add(Request $request)
    {
//        dd(json_decode('{"xxx":"yyy"}',true));
        $covoiturage=new Covoiturage();
        $manager=$this->getDoctrine()->getManager();
        $user=$manager->getRepository('App:User')->findOneById($this->getUser()->getId());
        $covoiturage->setOwner($user);

        $form=$this->createFormBuilder()
            ->add('moreDetails',TextType::class)
            ->add('add',SubmitType::class,[
                "attr"=>[
                    "onclick"=>'jsonPoints()'
                ]
            ])
            ->add('points',HiddenType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $points=json_decode($form->get('points')->getViewData(),true);
            foreach ($points as $element){
                $point=new MapPoint();
                $point->setX($element['x']);
                $point->setY($element['y']);
                $point->setCovoiturage($covoiturage);
                $manager->persist($point);

            }
           $manager->persist($covoiturage);
            $manager->flush();
        }


            return $this->render('covoiturage/addCovoiturage.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
