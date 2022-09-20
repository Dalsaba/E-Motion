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
    public function espace_client(): Response{
        return $this->render('espace_client/index.html.twig', [
        ]);
}

# Fonction affichage historique client
#[Route('/historique_commande', name: 'app_historique_commande')]
public function historique_cmd(Location $user = null, Request $request, ManagerRegistry $doctrine){
    $ClientID = $request->attributes->get('_route_params');
    //$Statut = $request->attributes->get('_route_params');
    // Récupération des commandes liés à l'utilisateurs
    $ems = $doctrine->getManager();
    $usersCommande = $ems->getRepository(Location::class)->findBy($ClientID);
    //$notPaid = $ems ->getRepository(Panier::class)-> notPaidOrder($Statut);

    return $this->render('espace_client/historique_cmd.html.twig', [
        'paniers' => $usersCommande,
        //'notPaid' => $notPaid,
    ]);
}

# Fonction modification information personel client
#[Route('/modif_info/{id}', name: 'app_modif_info')]
public function modif_info(Request $request, ManagerRegistry $doctrine){

    $id = $request->get('id');
    $user = $doctrine->getRepository(Client::class)->find($id);
    /*
    if (!$user) {
        throw new NotFoundHttpException("Page not found");
    }*/
    $form = $this->createForm(ClientType::class,$user);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $em = $doctrine->getManager();
        $UpdateInformation = $form->getData();
        $em->persist($UpdateInformation);
        $em->flush();
    }
    return $this->render('espace_client/modif_info.html.twig', [
        'UpdateInformation' => $form->createView()
    ]);
}

# Fonction inscription client
    #[Route('/ajouter_vehicule', name: 'app_ajout_vehicule')]
    public function addVehicule(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $vehicule = $em->getRepository(Vehicule::class)->findAll();

        $vehicule = new Vehicule();

        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($vehicule);
            $em->flush();
        }
        return $this->render('espace_client/ajout_vehicule.html.twig', [
            'Vehicule' => $form->createView(),
        ]);
    }

#Fonction déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
