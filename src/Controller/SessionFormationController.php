<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionFormationController extends AbstractController
{
    /**
     * @Route("/session/formation", name="app_session_formation")
     */
    public function index(): Response
    {
        return $this->render('session_formation/index.html.twig', [
            'controller_name' => 'SessionFormationController',
        ]);
    }
}
