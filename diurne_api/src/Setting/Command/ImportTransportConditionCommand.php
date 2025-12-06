<?php

namespace App\Setting\Command;

use Exception;
use App\Common\Service\CsvImporterService;
use App\Setting\Entity\TransportCondition;
use App\Setting\Entity\TransportConditionLang;
use App\Setting\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import-transportconditions', description: 'Import TransportCondition from CSV file')]
class ImportTransportConditionCommand extends Command
{
    private array $languages;

    public function __construct(
        private readonly CsvImporterService $csvImporterService,
        private readonly EntityManagerInterface $entityManager,
        private readonly LanguageRepository $languageRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvFilePath = __DIR__ . '/../Resource/T_CONDITION_TRANSPORT.csv';

        $this->languages = [
            'EN' => $this->languageRepository->findOneBy(['iso_code' => 'en']),
            'FR' => $this->languageRepository->findOneBy(['iso_code' => 'fr']),
            'ALL' => $this->languageRepository->findOneBy(['iso_code' => 'de']),
        ];

        try {
            $data = $this->csvImporterService->readCsvFile($csvFilePath, ';');
            $this->processImport($data);
            $io->success('TransportConditions data imported successfully.');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function processImport(array $data): void
    {
        foreach ($data as $row) {
            $transportCondition = new TransportCondition();
            $transportCondition->setName($row['LIBELLE_CONDITION_TRANSPORT']);
            $this->entityManager->persist($transportCondition);

            $transportConditionLang = new TransportConditionLang();
            $transportConditionLang->setLabel($row['LIBELLE_CONDITION_TRANSPORT']);
            $transportConditionLang->setDescription($row['DESCRIPTION_CONDITION_TRANSPORT']);
            $transportConditionLang->setLanguage($this->languages[$row['LANGUE_CONDITION_TRANSPORT']]);
            $transportConditionLang->setTransportCondition($transportCondition);

            $this->entityManager->persist($transportConditionLang);
            $this->entityManager->flush();
        }
    }
}
