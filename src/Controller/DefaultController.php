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
        $classes = $em -> getRepository(VehiculeClasse::class)-> findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'vehicule'=> $vehicule,
            'classes'=> $classes,

        ]);
    }

    #[Route('/not_found', name: 'app_not_found')]
    public function not_found(): Response
    {

        return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
            'controller_name' => 'error'
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
        $vehiculeClasseInfo = $em -> getRepository(VehiculeClasse::class)-> findOneBy(['id' => $vehiculeClass]);

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
            $resa -> setStatut('Panier');
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
            'vehiculeClasseInfo' => $vehiculeClasseInfo,
            'ajout'=> $form -> createView(),  // retourne la version html du form

        ]);
    }




}
