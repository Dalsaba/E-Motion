<?php

namespace App\Command;

use App\Entity\Location;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

#[AsCommand(
    name: 'app:verif-locations',
    description: 'Envoie un e-mail pour chaque véhicule non rendu',
    hidden: false,
    aliases: ['app:check-locations']
)]
class VerifLocationsCommand extends Command
{
    private $currentDate;
    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->currentDate = new \DateTime("now");
        $this->em = $em;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupère les locations non rendues pour lesquelles
        // la date de fin est passée
        $locations = $this->em->getRepository(Location::class)->findBy(['Statut'=> 'Terminé']);
        
        // Pour chaque location non rendue, envoyer un mail
        foreach ($locations as $loc) {
            if ($loc->getDateDeFin() < $this->currentDate) {
                // Changement du statut de la location
                $loc->setStatut('Non rendu');

                // Récupération du prénom, de l'e-mail et de la date de fin
                $prenom = $loc->getClientID()->getPrenom();
                $adresseEmail = $loc->getClientID()->getEmail();
                $dateDeFin = $loc->getDateDeFin();

                // Envoi d'un e-mail
                $email = (new TemplatedEmail())
                    ->to(new Address($adresseEmail))
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject('Véhicule non rendu')

                    ->htmlTemplate('emails/alerte.html.twig')

                    ->context([
                        'prenom' => $prenom,
                        'dateDeFin' => $dateDeFin,
                    ])
                ;

                $this->mailer->send($email);
            }
            
            // Mise à jour de la base de données
            $this->em->flush();
        }
        
        /*
        $email = (new TemplatedEmail())
            ->to(new Address('test@example.com'))
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Véhicule non rendu')

            ->htmlTemplate('emails/alerte.html.twig')

            ->context([
                'username' => 'foo',
            ])
        ;

        */

        // Affiche plusieurs lignes dans la console
        $output->writeln([
            '============',
            'Vérifications des locations non rendues...',
            '============',
        ]);

        return Command::SUCCESS;
    }
}
