<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="app_stagiaire")
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
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
     * @Route("/stagiaire/{id}/delete", name="delete_stagiaire")
     * @IsGranted("ROLE_USER")
     */
    public function delete(ManagerRegistry $doctrine, Stagiaire $stagiaire): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($stagiaire);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_stagiaire');
    }

    /**
     * @Route("/stagiaire/{id}", name="show_stagiaire")
     * @IsGranted("ROLE_USER")
     */
    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire
        ]);
    }

}
