<?php

namespace App\Controller;

use App\Entity\SessionFormation;
use Doctrine\Persistence\ManagerRegistry;
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

        $sessions_formation  = $doctrine->getRepository(SessionFormation::class)->findBy([],["date_debut"=>"ASC"] );


        return $this->render('session_formation/index.html.twig', [
            'sessions_formation' => $sessions_formation
        ]);
    }
}
