<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Location;
use App\Entity\Vehicule;
use App\Entity\VehiculeClasse;
use App\Form\LocationType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;


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
            'controller_name' => 'VehiculeDetailController',
            'vehicule'=> $vehicule,

        ]);
    }



    #[Route('/reservation/{id}', name: 'reservation')]
    public function reservation (Vehicule $vehicule = null, Client $c = null, ManagerRegistry $doctrine, Request $request, UserInterface $user): Response
    {

        if ($vehicule == null) {
            $this-> addFlash('danger', 'Article introuvable');

            return $this -> redirectToRoute('app_default');
        }


        //connexion  à la base
        $em = $doctrine ->getManager() ;
        $idClient = $user->getId();
        $c = $em -> getRepository(Client::class)-> findOneBy(['id' => $idClient]);
        $vehiculeClass = $vehicule ->getClasse();


        // création d'un objet vide pour le form
        $resa = new Location();
        //creation d'un formaulaire sur le modele de CategorieType
        $form = $this->createForm(LocationType::class, $resa);
        //Demande au formaulaire d'analyser la requete HTTP
        $form -> handleRequest($request);
        // si le formulaire a été soumis
        if ($form-> isSubmitted()) {
            $diff = $resa ->getDateDeFin() -> diff($resa ->getDateDeDebut()); // on fait la diff
            $diff->format("days");
            $nb = $diff->days;
            $resa-> setPrix(($vehiculeClass ->getPrix()) * ($nb + 1));
            $resa -> setStatut('En cours');
            $resa-> setClientID($c);
            $resa-> addVehiculeID($vehicule);

            if ($form->isValid()) {
                // on sauvegarde en base
                $em -> persist($resa);

                // execution de la sauvegardre (equivalent du prepare et execute en PDO)
                $em -> flush();

                $this-> addFlash('sucess', 'Ajoutée au panier!');
            }



        }

        //recupération de la table Location - a placer apres a sauvegarde
        $reservation = $em -> getRepository(Location::class)-> findAll();






        return $this->render('default/reservation.html.twig', [
            'controller_name' => 'reservationController',
            'vehicule'=> $vehicule,
            'reservation' => $reservation,
            'ajout'=> $form -> createView(),  // retourne la version html du form

        ]);
    }


//    #[Route('/stripe', name: 'stripe')]
//    public function stripe ()
//    {
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
