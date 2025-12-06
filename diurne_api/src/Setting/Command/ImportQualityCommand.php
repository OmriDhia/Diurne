<?php

namespace App\Setting\Command;

use App\Setting\Entity\Quality;
use App\Setting\Entity\QualityLang;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\QualityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:import-quality',
    description: 'Import quality data from JSON file'
)]
class ImportQualityCommand extends Command
{
    public function __construct(
        private readonly QualityRepository $qualityRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Process quality.json
        $filePath = __DIR__ . '/../Resource/quality.json';
        $qualityArray = $this->processJsonFile($filePath, $io);

        if (!$qualityArray) {
            return Command::FAILURE;
        }

        $io->success('Quality data imported successfully.');

        return Command::SUCCESS;
    }

    private function processJsonFile(string $filePath, SymfonyStyle $io): array|false
    {
        if (!file_exists($filePath)) {
            $io->error('JSON file not found at path: ' . $filePath);

            return false;
        }

        $jsonData = file_get_contents($filePath);
        $data = json_decode($jsonData, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $io->error('Error decoding JSON: ' . json_last_error_msg());

            return false;
        }

        foreach ($data as $item) {
            $io->text('Processing quality: ' . $item['QUALITE']);

            $existingQuality = $this->qualityRepository->findOneBy(['name' => $item['QUALITE']]);
            if ($existingQuality) {
                $io->text('Quality already exists: ' . $item['QUALITE']);
                continue;
            }

            $quality = new Quality();
            $quality->setName($item['QUALITE'])
                ->setWeight($item['POIDS'] ?? null)
                ->setVelvetStandard($item['Standard_Velours'] ?? null);

            $descriptions = [
                'fr' => $item['DESCRIPTION_QUALITE'],
                'en' => $item['DESCRIPTION_QUALITE_AN'],
                'de' => $item['DESCRIPTION_QUALITE_ALL'],
            ];

            foreach ($descriptions as $langCode => $description) {
                if ($description) {
                    $language = $this->languageRepository->findOneBy(['iso_code' => $langCode]);
                    if ($language) {
                        $qualityLang = new QualityLang();
                        $qualityLang->setLanguage($language)
                            ->setDescription($description)
                            ->setQuality($quality);
                        $this->entityManager->persist($qualityLang);
                        $quality->addQualityLang($qualityLang);
                    }
                }
            }

            $this->entityManager->persist($quality);
        }

        $this->entityManager->flush();

        return $data;
    }
}
