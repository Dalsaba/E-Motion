<?php

namespace App\Controller;
use App\Entity\Location;
use App\Entity\Vehicule;
use Doctrine\Persistence\ManagerRegistry;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class LocationController extends AbstractController

// cette page gère le panier et l'historique car ce sont des locations avec le statut en cours et terminé

{


    #[Route('/panier', name: 'app_panier')]
    public function index(ManagerRegistry $doctrine, UserInterface $user): Response
    {
        $em = $doctrine ->getManager() ;
        $criteria = array_filter(array(
            'Statut'=> 'En cours',
            'ClientID' => $user->getId(),
        ));

        $location = $em->getRepository(Location::class)-> findBy(['Statut'=> 'En cours']);




//        $clientid = array_filter($location, function($value) {
//            return $value === 2;
//        }
//        );

        return $this->render('location/index.html.twig', [
            'controller_name' => 'PanierController',
            'location'=> $location,

        ]);
    }

}
