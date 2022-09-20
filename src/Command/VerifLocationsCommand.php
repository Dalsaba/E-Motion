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
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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
        $output->writeln([
            '',
            'Vérifications des locations non rendues...',
            '============',
        ]);

        // Récupère les locations payées
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
                    $this->mailer->send($email);
                    $output->writeln(['Mail envoyé à ' . $adresseEmail]);
                } catch (TransportExceptionInterface $e) {
                    // some error prevented the email sending; display an
                    // error message or try to resend the message
                    $output->writeln(['Échec de l\'envoi du mail.']);
                }
            }
            
            // Mise à jour de la base de données
            $this->em->flush();
        }

        $output->writeln(['============', '']);

        return Command::SUCCESS;
    }
}
