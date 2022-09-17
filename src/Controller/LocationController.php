<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController

// cette page gère le panier et l'historique car ce sont des locations avec le statut en cours et terminé

{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        return $this->render('location/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }
}
