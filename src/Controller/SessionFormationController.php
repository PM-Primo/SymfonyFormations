<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Stagiaire;
use App\Entity\SessionFormation;
use App\Form\SessionFormationType;
use App\Repository\StagiaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ModuleFormationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SessionFormationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/session/formation/{id}/delete", name="delete_session_formation")
     */
    public function delete(ManagerRegistry $doctrine, SessionFormation $session): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_session_formation');
    }
        
    
    /**
     * @Route("/session/formation/{id}", name="show_session_formation")
     */
    public function show(SessionFormation $session, ManagerRegistry $doctrine, StagiaireRepository $sr, ModuleFormationRepository $mr): Response
    {
        
        // $categories = $doctrine->getRepository(Categorie::class)->categoriesSession($session);
        $categories = $doctrine->getRepository(Categorie::class)->findBy([],[]); 
        $nonInscrits = $sr->findNonInscrits($session->getId());
        $nonProgrammes = $mr->findNonProgrammes($session->getId());
        
        return $this->render('session_formation/show.html.twig', [
            'session' => $session,
            'categories' => $categories,
            'nonInscrits' => $nonInscrits,
            'nonProgrammes' => $nonProgrammes
        ]);
    }


    /**
     * @Route("/session/formation/{idsess}/remove/{idstag}", name="removefrom_session_formation")
     * @ParamConverter("session", options={"mapping" : {"idsess": "id"}})
     * @ParamConverter("stagiaire", options={"mapping": {"idstag": "id"}})
     */
    public function removeParticipant(ManagerRegistry $doctrine, SessionFormation $session, Stagiaire $stagiaire){

        $em = $doctrine->getManager();
        $session->removeParticipant($stagiaire);
        $em->persist($session);//Pas nécessaire dans ce cas, il sert principalement lorsqu'un ajoute des choses en bdd (ici on en retire seulement)
        $em->flush();
        return $this->redirectToRoute('show_session_formation', ['id' => $session->getId()]);

    }

    /**
     * @Route("/session/formation/{idsess}/add/{idstag}", name="addto_session_formation")
     * @ParamConverter("session", options={"mapping" : {"idsess": "id"}})
     * @ParamConverter("stagiaire", options={"mapping": {"idstag": "id"}})
     */
    public function addParticipant(ManagerRegistry $doctrine, SessionFormation $session, Stagiaire $stagiaire){

        $em = $doctrine->getManager();
        $session->addParticipant($stagiaire);
        $em->persist($session);
        $em->flush();
        return $this->redirectToRoute('show_session_formation', ['id' => $session->getId()]);

    }



}
