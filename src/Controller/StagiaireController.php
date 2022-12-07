<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Entity\SessionFormation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/stagiaire/{idstag}/removefrom/{idsess}", name="removesessionfrom_stagiaire")
     * @ParamConverter("session", options={"mapping" : {"idsess": "id"}})
     * @ParamConverter("stagiaire", options={"mapping": {"idstag": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function removeParticipant(ManagerRegistry $doctrine, SessionFormation $session, Stagiaire $stagiaire){

        $em = $doctrine->getManager();
        $session->removeParticipant($stagiaire);
        $em->persist($session);//Pas nécessaire dans ce cas, il sert principalement lorsqu'un ajoute des choses en bdd (ici on en retire seulement)
        $em->flush();
        return $this->redirectToRoute('show_stagiaire', ['id' => $stagiaire->getId()]);

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
