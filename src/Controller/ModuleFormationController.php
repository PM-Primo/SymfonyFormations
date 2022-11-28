<?php

namespace App\Controller;

use App\Entity\ModuleFormation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleFormationController extends AbstractController
{
    /**
     * @Route("/module/formation", name="app_module_formation")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories  = $doctrine->getRepository(Categorie::class)->findBy([],["id"=>"ASC"]);
        $modules  = $doctrine->getRepository(ModuleFormation::class)->findBy([],["categorie"=>"ASC"]);
        

        return $this->render('module_formation/index.html.twig', [
            'categories' => $categories,
            'modules' => $modules
        ]);
    }
}
