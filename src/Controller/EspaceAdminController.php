<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Location;
use App\Entity\Vehicule;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EspaceAdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_space')]
    public function dashboard(ManagerRegistry $d): Response
    {
        $em = $d->getManager();

        // Liste des clients
        $client = $em->getRepository(Client::class)->findAll();

        // Véhicule
        $vehicule = $em->getRepository(Vehicule::class)->findAll();

        return $this->render('espace_admin/index.html.twig', [
            'client' => $client,
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/admin/verif_locations', name: 'verif_locations')]
    public function verifLocations(MailerInterface $mailer, ManagerRegistry $d): Response
    {
        $em = $d->getManager();

        // Récupère les locations payées
        $locations = $em->getRepository(Location::class)->findBy(['Statut'=> 'Terminé']);

        // Pour chaque location non rendue, envoyer un mail
        foreach ($locations as $loc) {
            if ($loc->getDateDeFin() < new \DateTime("now")) {
                // Changement du statut de la location
                $loc->setStatut('Non rendu');

                // Récupération du prénom, de l'e-mail et de la date de fin
                $prenom = $loc->getClientID()->getPrenom();
                $adresseEmail = $loc->getClientID()->getEmail();
                $dateDeFin = $loc->getDateDeFin();

                // Envoi d'un e-mail
                $email = (new TemplatedEmail())
                    ->from(new Address('contact@e-motion.fr'))
                    ->to(new Address($adresseEmail))
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject('Véhicule non rendu')

                    ->htmlTemplate('emails/alerte.html.twig')

                    ->context([
                        'prenom' => $prenom,
                        'date_de_fin' => $dateDeFin,
                    ])
                ;

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    throw new \Exception($e);
                }
            }
            
            // Mise à jour de la base de données
            $em->flush();
        }

        $this->addFlash('success', 'Vérification des locations effectuée');
        return $this->redirectToRoute('app_admin_space');
    }
}
