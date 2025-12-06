<?php

namespace App\Setting\Command;

use App\Setting\Entity\Currency;
use App\Setting\Entity\Manufacturer;
use App\Setting\Repository\CountryRepository;
use App\Setting\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-manufacturers',
    description: 'Import manufacturers from CSV files.'
)]
class ImportManufacturersCommand extends Command
{
    private array $frenchToEnglishMap = [
        'Inde' => 'India',
        'Chine' => 'China',
        'France' => 'France',
        'Népal' => 'Nepal',
        'U-S' => 'United States',
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CountryRepository $countryRepository,
        private readonly CurrencyRepository $currencyRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Lire T_FABRIQUANT.csv
        $manufacturerCsvFile = __DIR__ . '/../../Setting/Resource/T_FABRIQUANT.csv';

        if (!file_exists($manufacturerCsvFile)) {
            $io->error('T_FABRIQUANT.csv non trouvé.');
            return Command::FAILURE;
        }

        // Lire les données du fichier CSV
        $manufacturerCsvData = $this->readCsv($manufacturerCsvFile, ',');
        foreach ($manufacturerCsvData as $row) {
            $manufacturer = new Manufacturer();
            $manufacturer->setName($row['NOM_FABRIQUANT']);
            $manufacturer->setCompany($row['ATELIER']);
            $manufacturer->setCarpetPrefix($row['INITIAL_TAPIS']);
            $manufacturer->setSamplePrefix($row['INITIAL_ECHANTILLON']);
            $manufacturer->setCreditAmount($row['MONTANT_AVOIR']);
            $manufacturer->setComplexityBonus((float) $row['BONUS_COMPLEXITE']);
            $manufacturer->setOversizeBonus((float) $row['BONUS_OVERSIZE']);
            $manufacturer->setOversizeMohaiBonus((float) $row['BONUS_OVERSIZE_MOHAIR']);
            $manufacturer->setMultiLevelBonus((float) $row['BONUS_MULTI_LEVEL']);
            $manufacturer->setSpecialFormBonus((float) $row['BONUS_SPECIAL_FORM']);

            // Gestion du pays
            $country = '' !== $row['LIEU'] ? $this->countryRepository->findOneBy(['name' => $this->frenchToEnglishMap[$row['LIEU']] ?? $row['LIEU']]) : null;

            // Gestion de la devise
            $currency = $this->currencyRepository->findOneBy(['name' => $row['TYPE_DEVISE']]);
            if (null === $currency && '' !== $row['TYPE_DEVISE']) {
                $currency = new Currency();
                $currency->setName($row['TYPE_DEVISE']);
                $this->entityManager->persist($currency);
            }

            $manufacturer->setCarpetCountry($country);
            $manufacturer->setCurrency($currency);

            // Persister le fabricant
            $this->entityManager->persist($manufacturer);
        }

        // Exécuter toutes les requêtes SQL en une fois
        $this->entityManager->flush();

        $io->success('Importation des fabricants terminée.');

        return Command::SUCCESS;
    }

    /**
     * Lire un fichier CSV et retourner les données sous forme de tableau associatif.
     *
     * @param string $filePath  chemin vers le fichier CSV
     * @param string $delimiter délimiteur utilisé dans le fichier CSV
     */
    private function readCsv(string $filePath, string $delimiter): array
    {
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            stream_filter_append($handle, 'convert.iconv.ISO-8859-1/UTF-8');
            $headers = fgetcsv($handle, 1000, $delimiter);
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $data[] = array_combine($headers, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
