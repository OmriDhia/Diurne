<?php

namespace App\Setting\Command;

use DateTime;
use App\Setting\Entity\Carrier;
use App\Setting\Repository\CarrierRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-carriers',
    description: 'Import carriers from CSV files.'
)]
class ImportCarriersCommand extends Command
{
    public function __construct(private readonly CarrierRepository $carrierRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $carrierCsvFile = __DIR__ . '/../../Setting/Resource/T_TRANSPORTEUR.csv';
        if (!file_exists($carrierCsvFile)) {
            $io->error('T_TRANSPORTEUR.csv non trouvé.');

            return Command::FAILURE;
        }

        $manufacturerCsvData = $this->readCsv($carrierCsvFile, ',');
        foreach ($manufacturerCsvData as $row) {
            $carrier = new Carrier();
            $carrier->setName($row['LIBELLE_TRANSPORTEUR']);
            $carrier->setContact($row['CONTACTE']);
            $carrier->setFax($row['FAX']);
            $carrier->setEmail($row['EMAIL']);
            $carrier->setPhone($row['TEL']);
            $carrier->setAddress($row['ADRESSE']);
            $carrier->setCreatedAt(new DateTime());
            $carrier->setUpdatedAt(new DateTime());
            $this->carrierRepository->save($carrier, flush: true);
        }

        $io->success('Importation des Transporteurs est terminée.');

        return Command::SUCCESS;
    }

    /**
     * Lire un fichier CSV et retourner les données sous forme de tableau associatif en UTF-8.
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
