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
        $location = $em->getRepository(Location::class)-> findBy(['Statut'=> 'Panier', 'ClientID' => $user->getId()]);
        $totalPrice  = 0;
        foreach ($location as $news) {
            $totalPrice = ($news -> getPrix()) + $totalPrice;
        }




        return $this->render('location/index.html.twig', [
            'controller_name' => 'PanierController',
            'locationClient'=> $location,
            'totalPrice'=> $totalPrice,
        ]);
    }

    #[Route('/stripe', name: 'stripe')]
    public function stripe (ManagerRegistry $doctrine, UserInterface $user)
    {
        $em = $doctrine ->getManager() ;

        $location = $em->getRepository(Location::class)-> findBy(['Statut'=> 'Panier', 'ClientID' => $user->getId()]);
        $totalPrice  = 0;
        foreach ($location as $news) {
            $totalPrice = ($news -> getPrix()) + $totalPrice;
        }

        \Stripe\Stripe::setApiKey('sk_test_51LPM2ZHIeun4UQSx1c8tVHphOFAeC7F8vQDgcWatwsACH4BCirrbC9APtRIvNpY6io7mnme5561jy85EpBY2pu3C00N2DCcB9g');

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:8000';

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price' => 'price_1LjiWAHIeun4UQSxmvR2lq7i',
                'quantity' => 1 * $totalPrice,

            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success',
            'cancel_url' => $YOUR_DOMAIN . '/cancel',
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
        $this -> result = $checkout_session->url;

        return $this -> redirect($this -> result) ;

    }

    #[Route('/historique', name: 'app_historique')]
    public function historique(ManagerRegistry $doctrine, UserInterface $user): Response
    {

        $em = $doctrine ->getManager() ;
        $location = $em->getRepository(Location::class)-> findBy(['Statut'=> 'Terminé', 'ClientID' => $user->getId()]);


        return $this->render('location/historique.html.twig', [
            'controller_name' => 'PanierController',
            'locationClient'=> $location,
        ]);
    }

    #[Route('/cancel', name: 'app_cancel')]
    public function cancel(): Response
    {

        return $this->render('location/cancel.html.twig');
    }

    #[Route('/success', name: 'app_success')]
    public function success(ManagerRegistry $doctrine, UserInterface $user): Response
    {
        $em = $doctrine ->getManager() ;
        $location = $em->getRepository(Location::class)-> findBy(['Statut'=> 'Panier', 'ClientID' => $user->getId()]);
        foreach ($location as $data){

            $data->setStatut('Terminé');

        }

        $em -> flush();


        return $this->render('location/success.html.twig');
    }

    #[Route('/panier/delete/{id}', name: 'panier_delete')]
    public function delete(Location $location = null, ManagerRegistry $d): Response{
        if ($location == null) {
            $this-> addFlash('danger', 'Produit introuvable');

            return $this -> redirectToRoute('app_default');
        }

        $em= $d -> getManager();
        $em -> remove($location);
        $em -> flush();
        $this->addFlash('warning', 'Panier vidée');



        return $this -> redirectToRoute('app_default') ;
    }




}
