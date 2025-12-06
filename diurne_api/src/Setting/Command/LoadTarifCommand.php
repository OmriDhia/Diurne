<?php

namespace App\Setting\Command;

use App\Setting\Entity\Tarif;
use App\Setting\Entity\TarifGroup;
use App\Setting\Entity\TarifTexture;
use App\Setting\Repository\CollectionGroupRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\TarifGroupRepository;
use App\Setting\Repository\TarifTextureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[\Symfony\Component\Console\Attribute\AsCommand(
    name: 'app:import-tarif',
    description: 'Load CollectionGroupPrice from CSV file.'
)]
class LoadTarifCommand extends Command
{
    public function __construct(
        private readonly CollectionGroupRepository $collectionGroupRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly TarifGroupRepository $tarifGroupRepository,
        private readonly TarifTextureRepository $tarifTextureRepository,
        private readonly DiscountRuleRepository $discountRuleRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvFilePath = __DIR__ . '/../Resource/T_TARIF.csv';

        if (!file_exists($csvFilePath)) {
            $io->error('CSV file not found: ' . $csvFilePath);
            return Command::FAILURE;
        }

        $this->loadTarifTexture();
        $this->loadTarifGroup();

        if (($handle = fopen($csvFilePath, 'r')) !== false) {
            $headers = fgetcsv($handle, 1000, ','); // Get headers

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $row = array_combine($headers, $data);
                $tarif = new Tarif();

                $this->setTarifProperties($tarif, $row);
                $this->entityManager->persist($tarif);
            }

            fclose($handle);
            $this->entityManager->flush();

            $io->success('Tarif loaded successfully.');
            return Command::SUCCESS;
        }

        $io->error('Could not open the CSV file.');
        return Command::FAILURE;
    }

    private function loadTarifTexture(): void
    {
        $tarifTextures = [
            ['ID_TARIF_TEXTURE' => 2, 'NOM_TARIF_TEXTURE' => '2016 n°1'],
            ['ID_TARIF_TEXTURE' => 3, 'NOM_TARIF_TEXTURE' => '2017 n°1'],
            ['ID_TARIF_TEXTURE' => 4, 'NOM_TARIF_TEXTURE' => '2017 n°2'],
            ['ID_TARIF_TEXTURE' => 5, 'NOM_TARIF_TEXTURE' => '2018'],
            ['ID_TARIF_TEXTURE' => 6, 'NOM_TARIF_TEXTURE' => '2019'],
            ['ID_TARIF_TEXTURE' => 7, 'NOM_TARIF_TEXTURE' => '2020'],
            ['ID_TARIF_TEXTURE' => 8, 'NOM_TARIF_TEXTURE' => '2021'],
            ['ID_TARIF_TEXTURE' => 9, 'NOM_TARIF_TEXTURE' => '2022'],
        ];

        foreach ($tarifTextures as $item) {
            $tarifTex = $this->tarifTextureRepository->findOneByYear($item['NOM_TARIF_TEXTURE']);
            if (!$tarifTex) {
                $tarifTex = new TarifTexture();
                $tarifTex->setYear($item['NOM_TARIF_TEXTURE']);
                $this->entityManager->persist($tarifTex);
            }
        }

        $this->entityManager->flush();
    }

    private function loadTarifGroup(): void
    {
        $tarifGroups = [
            ['ID_TARIF_GROUPE' => 3, 'ANNEE_TARIF_GROUPE' => '2016 n°1'],
            ['ID_TARIF_GROUPE' => 4, 'ANNEE_TARIF_GROUPE' => '2017 n°1'],
            ['ID_TARIF_GROUPE' => 5, 'ANNEE_TARIF_GROUPE' => '2016 n°2'],
            ['ID_TARIF_GROUPE' => 8, 'ANNEE_TARIF_GROUPE' => '2018 n°1'],
            ['ID_TARIF_GROUPE' => 9, 'ANNEE_TARIF_GROUPE' => '2019'],
            ['ID_TARIF_GROUPE' => 10, 'ANNEE_TARIF_GROUPE' => '2020'],
            ['ID_TARIF_GROUPE' => 11, 'ANNEE_TARIF_GROUPE' => '2021'],
            ['ID_TARIF_GROUPE' => 12, 'ANNEE_TARIF_GROUPE' => '2022'],
        ];

        foreach ($tarifGroups as $item) {
            $tarifGroup = $this->tarifGroupRepository->findOneByYear($item['ANNEE_TARIF_GROUPE']);
            if (!$tarifGroup) {
                $tarifGroup = new TarifGroup();
                $tarifGroup->setYear($item['ANNEE_TARIF_GROUPE']);
                $this->entityManager->persist($tarifGroup);
            }
        }

        $this->entityManager->flush();
    }

    private function setTarifProperties(Tarif $tarif, array $row): void
    {
        $this->setTarifGroup($tarif, $row);
        $this->setTarifTexture($tarif, $row);
        $this->setDiscountRule($tarif, $row);
        $this->setBasePrice($tarif, $row);
        $this->setLabel($tarif, $row);
        $this->setAdditionalProperties($tarif, $row);
    }

    private function setTarifGroup(Tarif $tarif, array $row): void
    {
        if (!empty($row['ID_TARIF_GROUPE'])) {
            $tarifGroup = $this->tarifGroupRepository->findOneBy(['year' => $row['ID_TARIF_GROUPE']]);
            if ($tarifGroup) {
                $tarif->setTarifGroup($tarifGroup);
            }
        }
    }

    private function setTarifTexture(Tarif $tarif, array $row): void
    {
        if (!empty($row['ID_TARIF_TEXTURE'])) {
            $tarifTexture = $this->tarifTextureRepository->findOneBy(['year' => $row['ID_TARIF_TEXTURE']]);
            if ($tarifTexture) {
                $tarif->setTarifTexture($tarifTexture);
            }
        }
    }

    private function setDiscountRule(Tarif $tarif, array $row): void
    {
        if (!empty($row['ID_DISCOUNT'])) {
            $discountRule = $this->discountRuleRepository->findOneBy(['discount' => $row['ID_DISCOUNT']]);
            if ($discountRule) {
                $tarif->setDiscountRule($discountRule);
            }
        }
    }

    private function setBasePrice(Tarif $tarif, array $row): void
    {
        if (!empty($row['PRIX_BASE'])) {
            $tarif->setBasePrice((float) $row['PRIX_BASE']);
        }
    }

    private function setLabel(Tarif $tarif, array $row): void
    {
        if (!empty($row['LIBELLE_NOM_TARIF'])) {
            $tarif->setLabel($row['LIBELLE_NOM_TARIF']);
        }
    }

    private function setAdditionalProperties(Tarif $tarif, array $row): void
    {
        $tarif->setConfidential((bool) $row['CONFIDENTIEL'] ?? false);
        $tarif->setBasePricePercentage((float) $row['POURCENTAGE_PRIX_BASE'] ?? 0);
        $tarif->setVariation((int) $row['VARIATION'] ?? 0);
        $tarif->setVat((float) $row['TVA'] ?? 0);
        $tarif->setTarifBase((bool) $row['TARIF_BASE'] ?? false);
        $tarif->setTarifPro((bool) $row['TARIF_PRO'] ?? false);
    }
}
