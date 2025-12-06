<?php

namespace App\Setting\Command;

use DateTimeImmutable;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\CarpetCollectionLang;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Entity\Language;
use App\Setting\Entity\Police;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\CollectionGroupRepository;
use App\Setting\Repository\PoliceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-collections',
    description: 'Import collections from a JSON file.'
)]
class ImportCollectionsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PoliceRepository $policeRepository,
        private readonly CarpetCollectionRepository $carpetCollectionRepository,
        private readonly CollectionGroupRepository $collectionGroupRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Read the JSON file
        $jsonFile = __DIR__ . '/../Resource/collection.json';
        if (!file_exists($jsonFile)) {
            $output->writeln('<error>File collection.json not found.</error>');
            return Command::FAILURE;
        }

        $jsonData = file_get_contents($jsonFile);
        $collectionsData = json_decode($jsonData, true);

        $collectionGroups = $this->collectionGroupRepository->findAll();
        if (!empty($collectionGroups)) {
            foreach ($collectionGroups as $collectionGroup) {
                $this->entityManager->remove($collectionGroup);
            }
            $this->entityManager->flush();
        }

        // Iterate over each collection data and create or update CarpetCollection entities
        foreach ($collectionsData as $collectionData) {
            // Check if the carpet collection already exists by its unique code
            $carpetCollection = $this->carpetCollectionRepository->findOneBy(['code' => $collectionData['CODE_COLLECTION']]);

            if (null === $carpetCollection) {
                // Create a new CarpetCollection entity if it doesn't exist
                $carpetCollection = new CarpetCollection();
                $carpetCollection->setCreatedAt(new DateTimeImmutable());
            }

            $collectionGroup = $this->collectionGroupRepository->findOneBy(['groupNumber' => $collectionData['groupe']]);
            if (empty($collectionGroup)) {
                $collectionGroup = new CollectionGroup();
                $collectionGroup->setGroupNumber($collectionData['groupe']);
                $this->entityManager->persist($collectionGroup);
                $this->entityManager->flush();
            }

            // Update the existing or new CarpetCollection entity
            $carpetCollection
                ->setReference($collectionData['REF_COLLECTION'])
                ->setCode($collectionData['CODE_COLLECTION'])
                ->setShowGrid('Oui' === $collectionData['AFFICHER_GRILLE_COLLECTION'])
                ->setUpdatedAt(new DateTimeImmutable())
                ->setCollectionGroup($collectionGroup);

            // Set Police if ID_POLICE is available in the JSON data
            $policeID = $collectionData['ID_POLICE'] ?? null;
            if (null !== $policeID) {
                $police = $this->policeRepository->find((int) $policeID);
                if ($police instanceof Police) {
                    $carpetCollection->setPolice($police);
                }
            }

            // Persist the CarpetCollection entity
            $this->entityManager->persist($carpetCollection);

            // Create CarpetCollectionLang entity and associate Language
            $carpetCollectionLang = new CarpetCollectionLang();
            $carpetCollectionLang->setCarpetCollection($carpetCollection);
            $carpetCollectionLang
                ->setDescription($collectionData['DESCRIPTION_AN'])
                ->setLanguage($this->getOrCreateLanguage($collectionData['Gen_DESCRIPTION_AN'], $collectionData['Gen_DESCRIPTION_FR']));

            // Persist the CarpetCollectionLang entity
            $this->entityManager->persist($carpetCollectionLang);

            // Flush changes to the database
            $this->entityManager->flush();
        }

        // Output success message
        $output->writeln('<info>Collections and related languages imported successfully.</info>');

        return Command::SUCCESS;
    }

    private function getOrCreateLanguage(string $descriptionAn, string $descriptionFr): Language
    {
        // Fetch Language entity by description
        $languageRepository = $this->entityManager->getRepository(Language::class);
        $language = $languageRepository->findOneBy(['name' => $descriptionAn]);

        // If Language doesn't exist, create a new one
        if (!$language) {
            $language = new Language();
            $language
                ->setName($descriptionAn)
                ->setIsoCode('en'); // Set ISO code appropriately, assuming English

            // Persist new Language
            $this->entityManager->persist($language);
        }

        return $language;
    }
}
