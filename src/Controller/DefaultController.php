<?php

namespace App\Controller;

use App\Entity\Vehicule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine ->getManager() ;
        $vehicule = $em -> getRepository(Vehicule::class)-> findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'vehicule'=> $vehicule,

        ]);
    }

    #[Route('/vehicule_detail/{id}', name: 'vehicule_detail')]
    public function affichVehiculeDetail(Vehicule $vehicule = null, ManagerRegistry $doctrine): Response
    {

        if ($vehicule == null) {
            $this-> addFlash('danger', 'Article introuvable');

            return $this -> redirectToRoute('app_default');
        }


        return $this->render('default/vehicule_detail.html.twig', [
            'controller_name' => 'VehiculeDetail',
            'vehicule'=> $vehicule,

        ]);
    }



}
