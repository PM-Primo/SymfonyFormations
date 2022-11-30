<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\SessionFormation;
use App\Form\SessionFormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionFormationController extends AbstractController
{
    /**
     * @Route("/session/formation", name="app_session_formation")
     */
    public function index(ManagerRegistry $doctrine): Response
    {

        // $sessions  = $doctrine->getRepository(SessionFormation::class)->findBy([],["date_debut"=>"ASC"] );

        $sessionsPassees = $doctrine->getRepository(SessionFormation::class)->sessionsPassees();
        $sessionsEnCours = $doctrine->getRepository(SessionFormation::class)->sessionsEnCours();
        $sessionsAVenir = $doctrine->getRepository(SessionFormation::class)->sessionsAVenir();


        return $this->render('session_formation/index.html.twig', [
            'sessionsPassees' => $sessionsPassees,
            'sessionsEnCours' => $sessionsEnCours,
            'sessionsAVenir' => $sessionsAVenir
        ]);

    }


    /**
     * @Route("/session/formation/add", name="add_session_formation")
     * @Route("/session/formation/{id}/edit", name="edit_session_formation")
     */
    public function add(ManagerRegistry $doctrine ,SessionFormation $session = null, Request $request): Response
    {
        if(!$session){
            $session = new SessionFormation;
        }

        $form = $this->createForm(SessionFormationType::class, $session);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $session = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($session); //équivalent de prepare()
            $entityManager->flush(); //équivalent de exacute()

            return $this->redirectToRoute('app_session_formation');
        }

        //Vue pour afficher le formulaire d'ajout
        return $this->render('session_formation/add.html.twig', [
            'formAddSessionFormation' =>$form->createView(),
            'edit' => $session->getId()
        ]);

    }


    /**
     * @Route("/session/formation/{id}", name="show_session_formation")
     */
    public function show(SessionFormation $session, ManagerRegistry $doctrine): Response
    {

        // $categories = $doctrine->getRepository(Categorie::class)->categoriesSession($session);
        $categories = $doctrine->getRepository(Categorie::class)->findBy([],[]);

        return $this->render('session_formation/show.html.twig', [
            'session' => $session,
            'categories' => $categories
        ]);
    }
}
