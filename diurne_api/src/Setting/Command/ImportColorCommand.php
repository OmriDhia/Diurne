<?php

namespace App\Setting\Command;

use Exception;
use App\Common\Service\CsvImporterService;
use App\Setting\Entity\Color;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-colors',
    description: 'Import colors from a CSV file.'
)]
class ImportColorCommand extends Command
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
        $csvFilePath = __DIR__ . '/../Resource/T_COULEUR.csv';

        try {
            $data = $this->csvImporterService->readCsvFile($csvFilePath, ';');
            $this->processImport($data);
            $io->success('Colors data imported successfully.');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error('Error during import: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }

    private function processImport(array $data): void
    {
        foreach ($data as $row) {
            $color = new Color();
            $color->setReference($row['REF_COULEUR']);
            $color->setHexCode($row['LIBELLE_COULEUR']);
            $this->entityManager->persist($color);
        }

        // Batch flush to improve performance (flush once after all entities are persisted)
        $this->entityManager->flush();
    }
}
