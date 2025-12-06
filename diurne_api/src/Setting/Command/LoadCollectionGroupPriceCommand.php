<?php

namespace App\Setting\Command;

use App\Setting\Entity\CollectionGroup;
use App\Setting\Entity\CollectionGroupPrice;
use App\Setting\Entity\Tarif;
use App\Setting\Entity\TarifTexture;
use App\Setting\Repository\CollectionGroupPriceRepository;
use App\Setting\Repository\CollectionGroupRepository;
use App\Setting\Repository\TarifGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import-collection-group-price', description: 'Load CollectionGroupPrice from CSV file.')]
class LoadCollectionGroupPriceCommand extends Command
{
    public function __construct(
        private readonly CollectionGroupRepository $collectionGroupRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CollectionGroupPriceRepository $collectionGroupPriceRepository,
        private readonly TarifGroupRepository $tarifGroupRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvFilePath = __DIR__ . '/../Resource/T_ASS_COLLECTION_TARIF_GROUPE.csv';

        if (!file_exists($csvFilePath)) {
            $io->error('csv file not found: ' . $csvFilePath);

            return Command::FAILURE;
        }

        // Remove existing CollectionGroupPrices
        $collectionGroupPrices = $this->collectionGroupPriceRepository->findAll();
        foreach ($collectionGroupPrices as $collectionGroupPrice) {
            $this->entityManager->remove($collectionGroupPrice);
        }
        $this->entityManager->flush();

        // Open the CSV file
        if (($handle = fopen($csvFilePath, 'r')) !== false) {
            $headers = fgetcsv($handle, 1000, ','); // Read the header row

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $row = array_combine($headers, $data);

                // Define tarif groups
                $tarifGroup = [
                    ['ID_TARIF_GROUPE' => 3, 'ANNEE_TARIF_GROUPE' => '2016 n°1'],
                    ['ID_TARIF_GROUPE' => 4, 'ANNEE_TARIF_GROUPE' => '2017 n°1'],
                    ['ID_TARIF_GROUPE' => 5, 'ANNEE_TARIF_GROUPE' => '2016 n°2'],
                    ['ID_TARIF_GROUPE' => 8, 'ANNEE_TARIF_GROUPE' => '2018 n°1'],
                    ['ID_TARIF_GROUPE' => 9, 'ANNEE_TARIF_GROUPE' => '2019'],
                    ['ID_TARIF_GROUPE' => 10, 'ANNEE_TARIF_GROUPE' => '2020'],
                    ['ID_TARIF_GROUPE' => 11, 'ANNEE_TARIF_GROUPE' => '2021'],
                    ['ID_TARIF_GROUPE' => 12, 'ANNEE_TARIF_GROUPE' => '2022'],
                ];

                // Ensure tarif groups exist
                foreach ($tarifGroup as $item) {
                    $tarifG = $this->tarifGroupRepository->findOneByYear((string) $item['ANNEE_TARIF_GROUPE']);
                    if (empty($tarifG)) {
                        $tarifG = new TarifTexture();
                        $tarifG->setYear((string) $item['ANNEE_TARIF_GROUPE']);
                        $this->entityManager->persist($tarifG);
                    }
                }
                $this->entityManager->flush();

                // Process the current row
                $tarifGroupLabel = null;
                if (!empty($row['ID_TARIF_GROUPE'])) {
                    foreach ($tarifGroup as $item) {
                        if ((int) $item['ID_TARIF_GROUPE'] === (int) $row['ID_TARIF_GROUPE']) {
                            $tarifGroupLabel = $item['ANNEE_TARIF_GROUPE'];
                            break;
                        }
                    }
                    $tarifGroup = $this->tarifGroupRepository->findOneBy(['year' => (string) $tarifGroupLabel]);
                }

                $collectionGroup = $this->collectionGroupRepository->findOneBy(['groupNumber' => $row['GROUPE']]);
                if (empty($collectionGroup)) {
                    $collectionGroup = new CollectionGroup();
                    $collectionGroup->setGroupNumber($row['GROUPE']);
                    $this->entityManager->persist($collectionGroup);
                    $this->entityManager->flush();
                }

                if (!empty($tarifGroup) && !empty($collectionGroup)) {
                    $collectionGroupPrice = new CollectionGroupPrice();
                    $collectionGroupPrice->setTarifGroup($tarifGroup);
                    $collectionGroupPrice->setCollectionGroup($collectionGroup);
                    $collectionGroupPrice->setPrice((float) $row['TARIF']);
                    $collectionGroupPrice->setPriceMax((float) $row['TARIF_MAX']);
                    $this->entityManager->persist($collectionGroupPrice);
                }
            }

            // Close the file
            fclose($handle);

            // Flush all changes to the database
            $this->entityManager->flush();

            $io->success('CollectionGroupPrice loaded successfully.');

            return Command::SUCCESS;
        } else {
            $io->error('Could not open the CSV file.');

            return Command::FAILURE;
        }
    }
}
