<?php

namespace App\Setting\Command;

use App\Setting\Entity\TaxRule;
use App\Setting\Entity\TaxRuleLang;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\TaxRuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import-tax-rate', description: 'Import tax rule data from CSV file')]
class ImportTaxRateCommand extends Command
{
    public function __construct(
        private readonly TaxRuleRepository $taxRuleRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = __DIR__ . '/../Resource/T_TVA.csv';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            $io->error('CSV file not found or not readable at path: ' . $filePath);

            return Command::FAILURE;
        }

        $data = $this->parseCsvFile($filePath, $io);

        if (false === $data) {
            return Command::FAILURE;
        }

        $this->importTaxRules($data, $io);

        $io->success('Tva data imported successfully.');

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
            $header = fgetcsv($handle, 1000, ';'); // using semicolon as delimiter
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

    private function importTaxRules(array $data, SymfonyStyle $io): void
    {
        $languages = [
            'an' => $this->languageRepository->findOneBy(['iso_code' => 'en']),
            'fr' => $this->languageRepository->findOneBy(['iso_code' => 'fr']),
            'all' => $this->languageRepository->findOneBy(['iso_code' => 'de']),
        ];

        foreach ($data as $item) {
            $io->text('Processing tax rule: ' . $item['Taux']);

            $taxRule = $this->taxRuleRepository->findOneBy(['taxRate' => $item['Taux']]);
            if (!$taxRule) {
                $taxRule = new TaxRule();
            }

            $taxRule->setTaxRate((string) (float) $item['Taux']);
            $taxRule->setRate($item['Rate']);
            foreach (['an' => 'IDENTIFIANT_TVA_ANG', 'fr' => 'Identifiant', 'all' => 'IDENTIFIANT_TVA_ALL'] as $langKey => $field) {
                if (!empty($item[$field]) && $languages[$langKey]) {
                    $taxRuleLang = new TaxRuleLang();
                    $taxRuleLang->setLanguage($languages[$langKey])
                        ->setIdentification($item[$field])
                        ->setTaxRule($taxRule);

                    $this->entityManager->persist($taxRuleLang);
                    $taxRule->addTaxRuleLang($taxRuleLang);
                }
            }

            $this->entityManager->persist($taxRule);
        }

        $this->entityManager->flush();
    }
}
