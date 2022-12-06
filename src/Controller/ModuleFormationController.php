<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\ModuleFormation;
use App\Form\ModuleFormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleFormationController extends AbstractController
{
    /**
     * @Route("/module/formation", name="app_module_formation")
     * @IsGranted("ROLE_USER")
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

    /**
     * @Route("module/formation/add", name="add_module_formation")
     * @Route("/module/formation/{id}/edit", name="edit_module_formation")
     * @IsGranted("ROLE_USER")
     */
    public function add(ManagerRegistry $doctrine, ModuleFormation $module = null, Request $request){

        if(!$module){
            $module = new ModuleFormation;
        }

        $form = $this->createForm(ModuleFormationType::class, $module);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $module = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($module); //équivalent de prepare()
            $entityManager->flush(); //équivalent de exacute()

            return $this->redirectToRoute('app_module_formation');
        }

        //Vue pour afficher le formulaire d'ajout
        return $this->render('module_formation/add.html.twig', [
            'formAddModuleFormation' =>$form->createView(),
            'edit' => $module->getId()
        ]);

    }

    /**
     * @Route("/module/formation/{id}/delete", name="delete_module_formation")
     * @IsGranted("ROLE_USER")
     */
    public function delete(ManagerRegistry $doctrine, ModuleFormation $module): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($module);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_module_formation');

    }
}
