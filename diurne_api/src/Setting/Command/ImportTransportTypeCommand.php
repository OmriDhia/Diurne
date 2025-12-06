<?php

namespace App\Setting\Command;

use Exception;
use App\Common\Service\CsvImporterService;
use App\Setting\Entity\TransportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import-transporttypes', description: 'Import TransportType from CSV file')]
class ImportTransportTypeCommand extends Command
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
        $csvFilePath = __DIR__ . '/../Resource/T_TYPE_TRANSPORT.csv';

        try {
            $data = $this->csvImporterService->readCsvFile($csvFilePath, ';');
            $this->processImport($data);
            $io->success('TransportType data imported successfully.');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function processImport(array $data): void
    {
        foreach ($data as $row) {
            $transportType = new TransportType();
            $transportType->setName($row['LIBELLE']);
            $this->entityManager->persist($transportType);
            $this->entityManager->flush();
        }
    }
}
