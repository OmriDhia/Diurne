<?php

namespace App\Setting\Command;

use App\Setting\Entity\DominantColor;
use App\Setting\Repository\DominantColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-dominant-color',
    description: 'Import dominant color from a CSV file.'
)]
class ImportDominantColorCommand extends Command
{
    public function __construct(
        private readonly DominantColorRepository $dominantColorRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = __DIR__ . '/../Resource/T_COULEUR_DOMINANTE.csv';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            $io->error('CSV file not found or not readable at path: ' . $filePath);
            return Command::FAILURE;
        }

        $data = $this->parseCsvFile($filePath, $io);

        if ($data === false) {
            return Command::FAILURE;
        }

        $this->importDominantColor($data, $io);

        $io->success('Dominant color data imported successfully.');

        return Command::SUCCESS;
    }

    private function parseCsvFile(string $filePath, SymfonyStyle $io): array|false
    {
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = [
                'ID',
                'NAME',
                'CODEHEX',
            ];
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        } else {
            $io->error('Error reading CSV file: ' . $filePath);
            return false;
        }

        return $data;
    }

    private function importDominantColor(array $data, SymfonyStyle $io): void
    {
        foreach ($data as $item) {
            $io->text('Processing color: ' . $item['NAME']);

            $existingColor = $this->dominantColorRepository->findOneBy(['name' => $item['NAME']]);
            if ($existingColor) {
                $io->text('Color already exists: ' . $item['NAME']);
                continue;
            }

            $dominantColor = new DominantColor();
            $dominantColor->setName($item['NAME']);
            $dominantColor->setHexCode($item['CODEHEX']);
            $this->entityManager->persist($dominantColor);
        }

        $this->entityManager->flush();
    }
}
