<?php

namespace App\Contremarque\Command;

use App\Contremarque\Entity\UnitOfMeasurement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-units-of-measurement',
    description: 'Create units of measurement'
)]
class CreateUnitsOfMeasurementCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Define units of measurement
        $units = [
            'centimètre' => 'cm',
            'feet' => 'ft',
            'inch' => 'inch',
        ];

        foreach ($units as $name => $abbreviation) {
            $unit = new UnitOfMeasurement();
            $unit->setName($name);
            $unit->setAbbreviation($abbreviation);
            $unit->setUnit($name);
            $this->entityManager->persist($unit);
        }

        $this->entityManager->flush();

        $io->success('Units of measurement have been successfully created.');

        return Command::SUCCESS;
    }
}
