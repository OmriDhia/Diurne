<?php

declare(strict_types=1);

namespace App\Setting\Command;

use Exception;
use App\Common\Service\CsvImporterService;
use App\Setting\Entity\SpecialTreatment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import-specialtreatments', description: 'Import SpecialTreatment from CSV file')]
class ImportSpecialTreatmentCommand extends Command
{
    public function __construct(
        private readonly CsvImporterService $csvImporterService,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvFilePath = __DIR__ . '/../Resource/T_TYPE_TRAITEMENT.csv';

        try {
            // Read CSV data using the CsvImporterService
            $data = $this->csvImporterService->readCsvFile($csvFilePath, ';');
            $this->processImport($data);
            $io->success('SpecialTreatments data imported successfully.');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function processImport(array $data): void
    {
        foreach ($data as $row) {
            $specialTreatment = new SpecialTreatment();
            $specialTreatment->setLabel($row['LIBELLE_TYPE_TRAITEMENT']);
            $specialTreatment->setPrice($row['PRIX_TYPE_TRAITEMENT']);
            $specialTreatment->setUnit('€/m²');
            $this->entityManager->persist($specialTreatment);
            $this->entityManager->flush();
        }
    }
}
