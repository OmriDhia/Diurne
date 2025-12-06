<?php

namespace App\Contremarque\Command;

use App\Contremarque\Entity\Mesurement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-measurement',
    description: 'Create measurement'
)]
class CreateMesurementCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Define units of measurement
        $measurements = [
            'Largeur',
            'Longueur',
            'Hauteur',
        ];

        foreach ($measurements as $name) {
            $measurement = new Mesurement();
            $measurement->setName($name);

            $this->entityManager->persist($measurement);
        }

        $this->entityManager->flush();

        $io->success('Measurements have been successfully created.');

        return Command::SUCCESS;
    }
}
