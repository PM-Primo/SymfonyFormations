<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="app_stagiaire")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $stagiaires  = $doctrine->getRepository(Stagiaire::class)->findBy([],["nom_stagiaire"=>"ASC"] );

        return $this->render('stagiaire/index.html.twig', 
        ['stagiaires' => $stagiaires]);
    }

    /**
     * @Route("/stagiaire/add", name="add_stagiaire")
     * @Route("/stagiaire/{id}/edit", name="edit_stagiaire")
     */
    public function add(ManagerRegistry $doctrine ,Stagiaire $stagiaire = null, Request $request): Response
    {
        if(!$stagiaire){
            $stagiaire = new Stagiaire;
        }

        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stagiaire = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($stagiaire); //équivalent de prepare()
            $entityManager->flush(); //équivalent de exacute()

            return $this->redirectToRoute('app_stagiaire');
        }

        //Vue pour afficher le formulaire d'ajout
        return $this->render('stagiaire/add.html.twig', [
            'formAddStagiaire' =>$form->createView(),
            'edit' => $stagiaire->getId()
        ]);

    }

    /**
     * @Route("/stagiaire/{id}", name="show_stagiaire")
     */
    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire
        ]);
    }

}
