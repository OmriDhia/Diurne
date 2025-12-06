<?php

namespace App\Setting\Command;

use App\Setting\Entity\Material;
use App\Setting\Entity\MaterialPrice;
use App\Setting\Repository\MaterialPriceRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\QualityRepository;
use App\Setting\Repository\QualityTarifTextureRepository;
use App\Setting\Repository\TarifTextureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:import-material-price'
)]
class ImportMaterialPriceCommand extends Command
{
    protected static $defaultName = 'app:import-material-price';
    private $materialData;
    private $materialPriceData;

    public function __construct(
        private readonly MaterialRepository $materialRepository,
        private readonly MaterialPriceRepository $materialPriceRepository,
        private readonly QualityTarifTextureRepository $qualityTarifTextureRepository,
        private readonly QualityRepository $qualityRepository,
        private readonly TarifTextureRepository $tarifTextureRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //        if (!$io->confirm('Do you imported materials ?  app:import-materials ?', false)) {
        //            $io->warning('use : bin/console app:import-materials to import materiels');
        //            return Command::SUCCESS;
        //        }

        $this->materialData = $this->parseCsvFile('material.csv', $io);
        $this->materialPriceData = $this->parseCsvFile('T_TARIF_MATIERE.csv', $io, ';');

        $this->importMaterialPrice($io);

        $io->success('Material Price data imported successfully.');

        return Command::SUCCESS;
    }

    private function parseCsvFile(string $fileName, SymfonyStyle $io, string $separator = ','): array
    {
        $filePath = __DIR__ . '/../Resource/' . $fileName;
        if (!file_exists($filePath) || !is_readable($filePath)) {
            $io->error('File not found at path: ' . $filePath);
            exit;
        }

        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, $separator); // using comma as the delimiter
            while (($row = fgetcsv($handle, 1000, $separator)) !== false) {
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        } else {
            $io->error('Error reading CSV file: ' . $filePath);
            exit;
        }

        return $data;
    }

    private function importMaterialPrice(SymfonyStyle $io): void
    {
        $qualityTarifTextures = [
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 125,
                'QUALITE' => 'Jhansi',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 126,
                'QUALITE' => 'Quilon',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 127,
                'QUALITE' => 'Gadag',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 128,
                'QUALITE' => 'Belgaum',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 129,
                'QUALITE' => 'Nagpur',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 130,
                'QUALITE' => 'Jammu',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 131,
                'QUALITE' => 'Mandu',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 132,
                'QUALITE' => 'Katni',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 133,
                'QUALITE' => 'Kampur',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 134,
                'QUALITE' => 'Barmer',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 135,
                'QUALITE' => 'Alwar',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 136,
                'QUALITE' => 'Kota',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 137,
                'QUALITE' => 'Teor',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 139,
                'QUALITE' => 'Hand-Loom',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 141,
                'QUALITE' => 'Orai',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 142,
                'QUALITE' => 'Kulti',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 143,
                'QUALITE' => 'Metture',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 144,
                'QUALITE' => 'Mysore',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 145,
                'QUALITE' => 'Ranchi',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 146,
                'QUALITE' => 'Gwalior',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 147,
                'QUALITE' => 'Perennials',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 148,
                'QUALITE' => 'Ajmer',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 149,
                'QUALITE' => 'Simia',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 150,
                'QUALITE' => 'Kulu',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 151,
                'QUALITE' => 'Mahathuha',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 152,
                'QUALITE' => 'Bundi',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 153,
                'QUALITE' => 'Aubusson',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 154,
                'QUALITE' => 'Jhali',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 155,
                'QUALITE' => 'Tissage',
                'TARIF_TEXTURE' => '2022',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 156,
                'QUALITE' => 'Metture',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 157,
                'QUALITE' => 'Mysore',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 158,
                'QUALITE' => 'Ranchi',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 159,
                'QUALITE' => 'Gwalior',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 160,
                'QUALITE' => 'Perennials',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 161,
                'QUALITE' => 'Ajmer',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 162,
                'QUALITE' => 'Simia',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 163,
                'QUALITE' => 'Kulu',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 164,
                'QUALITE' => 'Mahathuha',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 165,
                'QUALITE' => 'Bundi',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 166,
                'QUALITE' => 'Aubusson',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 167,
                'QUALITE' => 'Jhali',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 168,
                'QUALITE' => 'Tissage',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 107,
                'QUALITE' => 'Jhansi',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 108,
                'QUALITE' => 'Quilon',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 109,
                'QUALITE' => 'Gadag',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 110,
                'QUALITE' => 'Belgaum',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 111,
                'QUALITE' => 'Nagpur',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 112,
                'QUALITE' => 'Jammu',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 113,
                'QUALITE' => 'Mandu',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 114,
                'QUALITE' => 'Katni',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 115,
                'QUALITE' => 'Kampur',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 116,
                'QUALITE' => 'Barmer',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 117,
                'QUALITE' => 'Alwar',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 118,
                'QUALITE' => 'Kota',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 119,
                'QUALITE' => 'Teor',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 121,
                'QUALITE' => 'Hand-Loom',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 123,
                'QUALITE' => 'Orai',
                'TARIF_TEXTURE' => '2021',
            ],
            [
                'ID_ASS_QUALITE_TARIF_TEXTURE' => 124,
                'QUALITE' => 'Kulti',
                'TARIF_TEXTURE' => '2021',
            ],
        ];
        $materialPrices = $this->materialPriceRepository->findAll();
        if (!empty($materialPrices)) {
            foreach ($materialPrices as $materialPrice) {
                $this->entityManager->remove($materialPrice);
            }
            $this->entityManager->flush();
        }
        foreach ($this->materialPriceData as $item) {
            $materialId = $item['ID_MATIERE'];
            $material = $this->findMaterialFromData((int) $materialId);
            if ($material) {
                foreach ($qualityTarifTextures as $qualityTarifTexture) {
                    if ((int) $qualityTarifTexture['ID_ASS_QUALITE_TARIF_TEXTURE'] === (int) $item['ID_ASS_QUALITE_TARIF_TEXTURE']) {
                        $quality = $this->qualityRepository->findQualityByName((string) $qualityTarifTexture['QUALITE']);
                        $tarifTexture = $this->tarifTextureRepository->findOneByYear((string) $qualityTarifTexture['TARIF_TEXTURE']);
                        break;
                    }
                }

                $materialPrice = new MaterialPrice();
                if (!empty($quality) && !empty($tarifTexture)) {
                    $qualityTarifTexture = $this->qualityTarifTextureRepository->findOneBy(['quality' => $quality, 'tarifTexture' => $tarifTexture]);
                    $materialPrice->setQualityTarifTexture($qualityTarifTexture);
                }
                $materialPrice->setMaterial($material);
                $materialPrice->setPublicPrice((float) $item['TARIF_PUBLIC']);
                $materialPrice->setBigProjectPrice((float) $item['TARIF_GRD_PROJET']);

                $this->entityManager->persist($materialPrice);
            }
        }

        $this->entityManager->flush();
    }

    private function findMaterialFromData(int $materialId): ?Material
    {
        foreach ($this->materialData as $item) {
            if ($item['ID_MATIERE'] == $materialId) {
                $material = $this->materialRepository->findOneBy(['reference' => $item['LIBELLE_MATIERE']]);

                return $material;
            }
        }

        return null;
    }
}
