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
use Symfony\Component\HttpFoundation\Response;
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
            ->add('ajouter offre',SubmitType::class,[
                "attr"=>[
                    "onclick"=>'jsonPoints()'

                ]
            ])
            ->add('points',HiddenType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $covoiturage->setMoreDetails($form->get('moreDetails')->getViewData());
//            dd($covoiturage->getMoreDetails());
            $points=json_decode($form->get('points')->getViewData(),true);
            foreach ($points as $element){
                $point=new MapPoint();
                $point->setX($element['x']);
                $point->setY($element['y']);
                $point->setCovoiturage($covoiturage);
                $manager->persist($point);

            }

           $manager->persist($covoiturage);
            $this->addFlash('success','offre covoiturage ajouté avec succés');
            $manager->flush();
            return $this->redirect('/covoiturage/add');
        }


            return $this->render('covoiturage/addCovoiturage.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/covoiturage/show", name="showCovoiturage")
     */
    public function show()
    {
        return $this->render('covoiturage/showCovoiturage.html.twig');
    }

    /**
     * @Route("/covoiturage/getPoints", name="json points")
     */
    public function Points()
    {

        $manager=$this->getDoctrine()->getManager();
        $mapPoints=$manager->getRepository('App:MapPoint')->findAll();
        return new Response(json_encode($mapPoints));


    }

    /**
     * @Route("/covoiturage/getOwner", name="covoiturage owner")
     */
    public function owner(Request $request)
    {


        if($request->get('covoiturageId')!=null){
            $manager=$this->getDoctrine()->getManager();
            $covoiturage=$manager->getRepository('App:Covoiturage')->findOneById($request->get('covoiturageId'));
            $owner=$manager->getRepository('App:User')->findOneById($covoiturage->getOwner()->getId());

            return new Response('{"firstName":"'.$owner->getFirstName().'","lastName":"'.$owner->getLastName().'","email":"'.$owner->getEmail().'","moreDetails":"'.$covoiturage->getMoreDetails().'"}');
        }
        else return new Response('{}');




    }

}
