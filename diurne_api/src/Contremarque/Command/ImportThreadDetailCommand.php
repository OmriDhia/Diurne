<?php

namespace App\Contremarque\Command;

use Exception;
use App\Common\Service\CsvImporterService;
use App\Contremarque\Entity\ThreadDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-threaddetails',
    description: 'Import ThreadDetail from CSV file'
)]
class ImportThreadDetailCommand extends Command
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
            $io->success('ThreadDetails data imported successfully.');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function processImport(array $data): void
    {
        foreach ($data as $row) {
            $threadDetail = new ThreadDetail();
            $threadDetail->setName($row['name']);
            $this->entityManager->persist($threadDetail);
            $this->entityManager->flush();
        }
    }
}
