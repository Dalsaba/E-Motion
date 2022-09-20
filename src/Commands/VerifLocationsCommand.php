<?php

namespace App\Command;

use App\Entity\Location;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:verif-locations')]
class VerifLocationsCommand extends Command
{
    protected static $defaultName = 'app:verif-locations';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setDescription('Vérifie s\'il y a des véhicules non rendus')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $locations = $this->em->getRepository(Location::class)->findAll();
        

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}

