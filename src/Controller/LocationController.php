<?php

namespace App\Controller;
use App\Entity\Location;
use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        $location = $em->getRepository(Location::class)-> findBy(['Statut'=> 'En cours']);
        foreach ($location as $news) {
            $idClient = $news -> getClientID() -> getID();
        }
        $locationClient = null;
        if ($idClient = $user->getId()) {
            $locationClient = $location;
        }




        return $this->render('location/index.html.twig', [
            'controller_name' => 'PanierController',
            'locationClient'=> $locationClient,
            'idClient'=> $idClient,




        ]);
    }

//    #[Route('/stripe', name: 'stripe')]
//    public function stripe (ManagerRegistry $doctrine)
//    {
//        $em = $doctrine ->getManager() ;
//        $location = $em->getRepository(Location::class)-> findBy(['Statut'=> 'En cours']);
//
//        \Stripe\Stripe::setApiKey('sk_test_51LPM2ZHIeun4UQSx1c8tVHphOFAeC7F8vQDgcWatwsACH4BCirrbC9APtRIvNpY6io7mnme5561jy85EpBY2pu3C00N2DCcB9g');
//
//        header('Content-Type: application/json');
//
//        $YOUR_DOMAIN = 'http://localhost:8000/public';
//
//        $checkout_session = \Stripe\Checkout\Session::create([
//            'line_items' => [[
//                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
//                'price' => 'price_1LabVtHIeun4UQSxJphF25Wg',
//                'quantity' => 2,
//            ]],
//            'mode' => 'payment',
//            'success_url' => $YOUR_DOMAIN . '/success.html',
//            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
//        ]);
//
//        header("HTTP/1.1 303 See Other");
//        header("Location: " . $checkout_session->url);
//        $this -> result = $checkout_session->url;
//
//        return $this -> redirect($this -> result) ;
//
//    }



}
