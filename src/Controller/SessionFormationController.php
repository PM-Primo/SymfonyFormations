<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Entity\ModuleFormation;
use App\Entity\SessionFormation;
use App\Form\SessionFormationType;
use App\Repository\StagiaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ModuleFormationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SessionFormationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SessionFormationController extends AbstractController
{
    /**
     * @Route("/session/formation", name="app_session_formation")
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
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
            $entityManager->persist($session); //??quivalent de prepare()
            $entityManager->flush(); //??quivalent de exacute()

            return $this->redirectToRoute('show_session_formation', ['id' => $session->getId()]);
        }

        //Vue pour afficher le formulaire d'ajout
        return $this->render('session_formation/add.html.twig', [
            'formAddSessionFormation' =>$form->createView(),
            'edit' => $session->getId()
        ]);

    }
    
    
    /**
     * @Route("/session/formation/{id}/delete", name="delete_session_formation")
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
     */
    public function removeParticipant(ManagerRegistry $doctrine, SessionFormation $session, Stagiaire $stagiaire){

        $em = $doctrine->getManager();
        $session->removeParticipant($stagiaire);
        $em->persist($session);//Pas n??cessaire dans ce cas, il sert principalement lorsqu'un ajoute des choses en bdd (ici on en retire seulement)
        $em->flush();
        return $this->redirectToRoute('show_session_formation', ['id' => $session->getId()]);

    }

    /**
     * @Route("/session/formation/{idsess}/add/{idstag}", name="addto_session_formation")
     * @ParamConverter("session", options={"mapping" : {"idsess": "id"}})
     * @ParamConverter("stagiaire", options={"mapping": {"idstag": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function addParticipant(ManagerRegistry $doctrine, SessionFormation $session, Stagiaire $stagiaire){

        $em = $doctrine->getManager();
        $session->addParticipant($stagiaire);
        $em->persist($session);
        $em->flush();
        return $this->redirectToRoute('show_session_formation', ['id' => $session->getId()]);

    }

    /**
     * @Route("/session/formation/{idsess}/deleteprog/{idprog}", name="delete_programme")
     * @ParamConverter("session", options={"mapping" : {"idsess": "id"}})
     * @ParamConverter("programme", options={"mapping": {"idprog": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function deleteProgramme(ManagerRegistry $doctrine, SessionFormation $session, Programme $programme): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($programme);
        $entityManager->flush();
    
        return $this->redirectToRoute('show_session_formation', ['id' => $session->getId()]);
    }

    /**
     * @Route("/session/formation/{idsess}/addprog/{idmod}", name="add_programme")
     * @ParamConverter("session", options={"mapping" : {"idsess": "id"}})
     * @ParamConverter("module", options={"mapping": {"idmod": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function addProgramme(ManagerRegistry $doctrine, SessionFormation $session, ModuleFormation $module): Response
    {

        $em = $doctrine->getManager();

        $nbJours = filter_input(INPUT_POST, "nbJours", FILTER_VALIDATE_INT);

        $programme = new Programme;
        $programme->setNbJours($nbJours);
        $programme->setSessionFormation($session);
        $programme->setModuleFormation($module);
        $em->persist($programme);

        $session->addProgramme($programme);
        $em->persist($session);
        $em->flush();

        return $this->redirectToRoute('show_session_formation', ['id' => $session->getId()]);
    }



}
