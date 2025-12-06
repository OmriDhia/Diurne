<?php
// src/Contremarque/Command/ImportCarpetCompositionCommand.php
namespace App\Contremarque\Command;

use Exception;
use App\Common\Service\CsvImporterService;
use App\Contremarque\Entity\CarpetComposition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-carpetcompositions',
    description: 'Import CarpetComposition from CSV file'
)]
class ImportCarpetCompositionCommand extends Command
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
        $csvFilePath = __DIR__ . '/../Resource/T_TYPE_FILE.csv';

        try {
            $data = $this->csvImporterService->readCsvFile($csvFilePath, ';');
            $this->processImport($data);
            $io->success('CarpetCompositions data imported successfully.');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function processImport(array $data): void
    {
        foreach ($data as $row) {
            $carpetComposition = new CarpetComposition();
            $carpetComposition->setName($row['name']);
            $this->entityManager->persist($carpetComposition);
            $this->entityManager->flush();
        }
    }
}
