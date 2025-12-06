<?php

namespace App\Setting\Command;

use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\Model;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:import-models',
    description: 'Import models from CSV files.'
)]
class ImportModelsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ModelRepository $modelRepository,
        private readonly CarpetCollectionRepository $carpetCollectionRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Lire T_COLLECTION.csv
        $collectionCsvFile = __DIR__ . '/../../Setting/Resource/T_COLLECTION.csv';

        if (!file_exists($collectionCsvFile)) {
            $io->error('T_COLLECTION.csv non trouvé.');

            return Command::FAILURE;
        }

        // Lire les données du fichier CSV avec un séparateur de point-virgule
        $collectionCsvData = $this->readCsv($collectionCsvFile, ';');

        // Lire T_MODEL.csv
        $modelCsvFile = __DIR__ . '/../../Setting/Resource/T_MODEL.csv';

        if (!file_exists($modelCsvFile)) {
            $io->error('T_MODEL.csv non trouvé.');

            return Command::FAILURE;
        }

        // Lire les données du fichier CSV avec un séparateur de point-virgule
        $modelCsvData = $this->readCsv($modelCsvFile, ';');

        // Traiter les données de T_MODEL.csv
        foreach ($modelCsvData as $row) {
            $idCollection = (int) $row['ID_COLLECTION'];
            $refModel = $row['REF_MODEL'];
            // Autres champs si nécessaire

            // Trouver la collection par ID dans le tableau de collections
            $collectionReference = $this->findCollectionReferenceById($collectionCsvData, $idCollection);

            if (null === $collectionReference) {
                $io->warning(sprintf('Collection avec l\'ID "%d" non trouvée.', $idCollection));
                continue;
            }

            // Trouver la collection par ID
            $collection = $this->carpetCollectionRepository->findOneBy(['code' => strtoupper($collectionReference)]);

            if (!$collection instanceof CarpetCollection) {
                $io->warning(sprintf('Collection avec l\'ID "%d" non trouvée.', $idCollection));
                continue;
            }

            // Vérifier si le modèle existe déjà ou créer un nouveau
            $model = $this->modelRepository->findOneBy(['code' => $refModel]);

            if (!$model instanceof Model) {
                $model = new Model();
                $model->setCode($refModel);
                $model->setNumberMax((int) $row['NUM_MAX']);
                $model->setCarpetCollection($collection);
            }

            // Persister les changements si nécessaire
            $this->entityManager->persist($collection);
            $this->entityManager->persist($model);
            $this->entityManager->flush();
        }

        // Valider les changements dans la base de données
        $this->entityManager->flush();

        $io->success('Importation des modèles terminée.');

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

    /**
     * Trouver la référence d'une collection par son ID dans un tableau de collections.
     *
     * @param array $collections tableau de collections
     * @param int   $id          id de la collection à rechercher
     *
     * @return string|null la référence de la collection si trouvée, null sinon
     */
    private function findCollectionReferenceById(array $collections, int $id): ?string
    {
        foreach ($collections as $collection) {
            if ((int) $collection['ID_COLLECTION'] === $id) {
                return $collection['CODE_COLLECTION'];
            }
        }

        return null;
    }
}
