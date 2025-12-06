<?php

namespace App\Setting\Command;

use App\Setting\Entity\Material;
use App\Setting\Entity\MaterialLang;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:import-materials',
    description: 'Import material data from CSV file'
)]
class ImportMaterialsCommand extends Command
{
    public function __construct(
        private readonly MaterialRepository $materialRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = __DIR__ . '/../Resource/material.csv';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            $io->error('CSV file not found or not readable at path: ' . $filePath);

            return Command::FAILURE;
        }

        $data = $this->parseCsvFile($filePath, $io);

        if (false === $data) {
            return Command::FAILURE;
        }

        $this->importMaterials($data, $io);

        $io->success('Material data imported successfully.');

        return Command::SUCCESS;
    }

    private function parseCsvFile(string $filePath, SymfonyStyle $io): array|false
    {
        if (!file_exists($filePath)) {
            $io->error('CSV file not found at path: ' . $filePath);

            return false;
        }

        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ','); // using comma as the delimiter
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        } else {
            $io->error('Error reading CSV file: ' . $filePath);

            return false;
        }

        return $data;
    }

    private function importMaterials(array $data, SymfonyStyle $io): void
    {
        $languages = [
            'an' => $this->languageRepository->findOneBy(['iso_code' => 'en']),
            'fr' => $this->languageRepository->findOneBy(['iso_code' => 'fr']),
            'all' => $this->languageRepository->findOneBy(['iso_code' => 'de']),
        ];

        foreach ($data as $item) {
            $io->text('Processing material: ' . $item['LIBELLE_MATIERE']);

            $existingMaterial = $this->materialRepository->findOneBy(['reference' => $item['LIBELLE_MATIERE']]);
            if ($existingMaterial) {
                $io->text('Material already exists: ' . $item['LIBELLE_MATIERE']);
                continue;
            }

            $material = new Material();
            $material->setReference($item['LIBELLE_MATIERE']);

            foreach (['an' => 'LIBELLE_MATIERE_AN', 'fr' => 'LIBELLE_MATIERE_FR', 'all' => 'LIBELLE_MATIERE_ALL'] as $langKey => $field) {
                if (!empty($item[$field]) && $languages[$langKey]) {
                    $materialLang = new MaterialLang();
                    $materialLang->setLanguage($languages[$langKey])
                        ->setLabel($item[$field])
                        ->setMaterial($material);

                    $this->entityManager->persist($materialLang);
                    $material->addMaterialLang($materialLang);
                }
            }

            $this->entityManager->persist($material);
        }

        $this->entityManager->flush();
    }
}
