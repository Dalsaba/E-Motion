<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Location;
use App\Entity\Vehicule;
use App\Entity\VehiculeClass;

use App\Form\ClientType;
use App\Form\VehiculeType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\InscriptionClientType;
use App\Security\RegistrationAuthenticator;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class EspaceClientController extends AbstractController
{
# Fonction connexion client
    #[Route('/connexion_client', name: 'app_connexion_client')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response{
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('connexion_client/index.html.twig', [
            'lastUsername' => $lastUsername,
            'error'         => $error,
        ]);
    }

# Fonction inscription client
    #[Route('/inscription_client', name: 'app_inscription_client')]
    public function register(Request $request, UserPasswordHasherInterface $userHasherInfo, UserAuthenticatorInterface $userAuthenticator, RegistrationAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Client();
        $form = $this->createForm(InscriptionClientType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userHasherInfo->hashPassword(
                    $user,
                    $form->get('password')->getData(),
                )
            );
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
        return $this->render('inscription_client/index.html.twig', [
            'controller_name' => 'EspaceClientController',
            'InscriptionClient' => $form->createView(),
        ]);
    }
    
# Fonction fonctionnalite client
    #[Route('/espace_client', name: 'app_user_space')]
    public function espace_client(ManagerRegistry $doctrine, UserInterface $user): Response{

        $em = $doctrine ->getManager() ;
        $PointFide = $em->getRepository(Location::class)-> count(['Statut'=>'Terminé', 'ClientID' => $user->getId()]);

        return $this->render('espace_client/index.html.twig', [
            'PointFide' => $PointFide,
        ]);
}

# Fonction modification information personel client
#[Route('/modif_info/{id}', name: 'app_modif_info')]
public function modif_info(Request $request, ManagerRegistry $doctrine){

    $id = $request->get('id');
    $user = $doctrine->getRepository(Client::class)->find($id);
    
    $form = $this->createForm(ClientType::class,$user);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $em = $doctrine->getManager();
        $UpdateInformation = $form->getData();
        $em->persist($UpdateInformation);
        $em->flush();
        return $this->redirectToRoute('app_user_space');
    }
    return $this->render('espace_client/modif_info.html.twig', [
        'UpdateInformation' => $form->createView()
    ]);
}

# Fonction  Vehicule
    #[Route('/{id}/ajouter_vehicule', name: 'app_ajout_vehicule')]
    public function addVehicule(Request $request, ManagerRegistry $doctrine): Response
    {
        // Client
        $id = $request->get('id');
        #$em = $doctrine->getManager();
        #$client = $em->getRepository(Client::class)->findAll($id);
        #$role = "ROLE_PROPRIETAIRE";

        // Véhicule
        $em = $doctrine->getManager();
        $vehicule = $em->getRepository(Vehicule::class)->findAll();

        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        $user = $this->getUser();
        #$user -> setRoles(array('ROLE_USER',$role));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $vehicule -> setIdClient($user);
            $em->persist($vehicule);
            $em->flush();
            $this->addFlash("success", "Voiture enregistrée");
            return $this->redirectToRoute('app_mes_vehicules', array('id' => $id));
        }
        return $this->render('espace_client/ajout_vehicule.html.twig', [
            'Vehicule' => $form->createView(),
        ]);
    }
# Fonction affichage Mes véhicules
    #[Route('/{id}/MesVehicules', name: 'app_mes_vehicules')]
    public function MesVehicules(String $id = null,ManagerRegistry $doctrine): Response{
        $em = $doctrine ->getManager() ;
        $vehicule = $em->getRepository(Vehicule::class)-> findBy(['id_client'=> $id]);
        foreach ($vehicule as $data) {
            $idVehicule = $data -> getId();
            $immatricule = $data -> getImmatricule();
            $marque = $data -> getMarque();
            $typeVehicule = $data -> getClasse();
            $modele = $data -> getId_Client();
            $num_serie = $data -> getNum_Serie();
            $couleur = $data -> getCouleur();
            $nb_kilometre = $data -> getNb_Kilometre();
            $date_achat = $data -> getDate_Achat();
            $prix_achat = $data -> getPrix_Achat();
        }
        return $this->render('espace_client/MesVehicules.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    #Fonction suppression Véhicule
    #[Route(path: '/MesVehicules/{id}', name: 'app_supp_vehicule')]
    public function deleteVehicule(Request $request, Vehicule $vehicule = null, ManagerRegistry $d): Response{
        $idUser = $request->get('idUser');
        if ($vehicule == null) {
            $this-> addFlash('danger', 'Vehicule introuvable');
            return $this -> redirectToRoute('app_mes_vehicules', array('id' => $idUser));
        }
        $em= $d -> getManager();
        $em -> remove($vehicule);
        $em -> flush();
        $this->addFlash('warning', 'Vehicule supprimée');
        return $this->redirectToRoute('app_mes_vehicules', array('id' => $idUser));
    }

    #Fonction modif Véhicule
    #[Route(path: '/MesVehicules/edit/{id}', name: 'app_modif_vehicule')]
    public function modificationVehicule(Request $request, ManagerRegistry $doctrine, Vehicule $vehicule = null): Response{
        $idUser = $request->get('idUser');
        $id = $request->get('id');

        if($vehicule == null){
            $this-> addFlash('danger', 'Vehicule introuvable');
            return $this -> redirectToRoute('app_mes_vehicules', array('id' => $idUser));
        }

        $vehicule = $doctrine->getRepository(Vehicule::class)->find($id);
        $form = $this->createForm(VehiculeType::class,$vehicule);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $UpdateInformation = $form->getData();
            $em->persist($UpdateInformation);
            $em->flush();
            return $this -> redirectToRoute('app_mes_vehicules', array('id' => $idUser));
        }
        return $this->render('espace_client/modif_vehicule.html.twig', [
            'UpdateInformation' => $form->createView()
        ]);
    }

#Fonction déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
