<?php

declare(strict_types=1);

namespace App\Setting\Command;

use App\Setting\Entity\ManufacturerPriceGrid;
use App\Setting\Repository\ManufacturerRepository;
use App\Setting\Repository\ManufacturerPriceRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\QualityRepository;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use App\Setting\Repository\TarifGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import:manufacturer-price-grids',
    description: 'Import manufacturer price grids ONLY from src/Setting/Resource/T_GT_ATELIER.xlsx'
)]
class ImportManufacturerPriceGridsCommand extends Command
{
    private const BATCH_SIZE = 50;
    private const MATERIAL_COLUMN_MAPPING = [
        'Wool' => 8,
        'Silk' => 9,
    ];

    public function __construct(
        private readonly EntityManagerInterface          $entityManager,
        private readonly ManufacturerRepository          $manufacturerRepository,
        private readonly QualityRepository               $qualityRepository,
        private readonly ManufacturerPriceGridRepository $priceGridRepository,
        private readonly ManufacturerPriceRepository     $manufacturerPriceRepository,
        private readonly MaterialRepository              $materialRepository,
        private readonly TarifGroupRepository            $tarifGroupRepository,
        private readonly string                          $projectDir
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Importing Manufacturer Price Grids (T_GT_ATELIER only)');

        $resourcePath = $this->projectDir . '/src/Setting/Resource/';

        try {
            $result = $this->importData($resourcePath, $io);

            $io->success([
                'Import completed successfully!',
                sprintf('Imported: %d price grid entries', $result['imported']),
                sprintf('Skipped: %d entries (missing relations or duplicates)', $result['skipped']),
                sprintf('Total processed: %d rows', $result['total'])
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error([
                'Import failed!',
                'Error: ' . $e->getMessage(),
                'File: ' . $e->getFile() . ':' . $e->getLine()
            ]);
            return Command::FAILURE;
        }
    }

    private function importData(string $resourcePath, SymfonyStyle $io): array
    {
        $gtAtelierFile = $resourcePath . 'T_GT_ATELIER.xlsx';

        if (!file_exists($gtAtelierFile)) {
            throw new \RuntimeException('File not found: T_GT_ATELIER.xlsx');
        }

        $io->writeln('Loading T_GT_ATELIER.xlsx...');

        // Load the single Excel file
        $gtAtelierSpreadsheet = IOFactory::load($gtAtelierFile);

        // Read rows
        $gtAtelierSheet = $gtAtelierSpreadsheet->getActiveSheet();
        $data = $gtAtelierSheet->toArray();

        // Remove header row
        array_shift($data);

        // --- Clear existing manufacturer price data before importing ---
        $io->writeln('Clearing existing manufacturer_price and manufacturer_price_grid records...');
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            // Delete child table first to avoid FK constraint issues
            $conn->executeStatement('DELETE FROM manufacturer_price');
            $conn->executeStatement('DELETE FROM manufacturer_price_grid');
            $conn->commit();
            // Clear the ORM to avoid stale managed entities
            $this->entityManager->clear();
            $io->writeln('Existing manufacturer price data cleared.');
        } catch (\Throwable $e) {
            $conn->rollBack();
            throw new \RuntimeException('Failed to clear existing manufacturer price data: ' . $e->getMessage());
        }
        // --- end purge ---

        $importedCount = 0;
        $skippedCount = 0;
        $totalRows = count($data);

        $io->progressStart($totalRows);

        foreach ($data as $index => $row) {
            $io->progressAdvance();

            // Read raw incoming identifiers (may be numeric ids or external codes)
            $rawCol0 = $row[0] ?? null; // original value in column 0 (unused for import decision)
            $idFabriquant = (int)($row[1] ?? 0);
            $idQualite = (int)($row[2] ?? 0);
            $annee = (int)($row[3] ?? 0); // contains tarif_group id

            // If both manufacturer and quality are absent, skip but log details
            if ($idFabriquant <= 0 && $idQualite <= 0) {
                $skippedCount++;
                if ($io->isVerbose()) {
                    $io->writeln(sprintf(
                        '  Skipped row %d: empty manufacturer and quality (raw row[0]=%s) - row data: %s',
                        $index + 2,
                        (string)$rawCol0,
                        json_encode($row)
                    ));
                }
                continue;
            }

            // Optional data
            $materialPrices = $this->extractMaterialPrices($row);

            try {
                // Lookup tarif group by id (you changed Excel column to hold tarif_group id)
                $tarifGroup = $this->tarifGroupRepository->find($annee);

                if (!$tarifGroup) {
                    $skippedCount++;
                    if ($io->isVerbose()) {
                        $io->writeln(sprintf(
                            '  Skipped row %d: tarif group id %s not found - row data: %s',
                            $index + 2,
                            $annee,
                            json_encode($row)
                        ));
                    }
                    continue;
                }

                // Check duplicates using the tarif group id
                $existingGrid = $this->priceGridRepository->findOneByManufacturerQualityAndTarifGroup(
                    $idFabriquant,
                    $idQualite,
                    $tarifGroup->getId()
                );

                if ($existingGrid) {
                    $skippedCount++;
                    if ($io->isVerbose()) {
                        $io->writeln(sprintf(
                            '  Skipped row %d: Grid already exists (manufacturer %d, quality %d, tarif_group_id %d) - row: %s',
                            $index + 2,
                            $idFabriquant,
                            $idQualite,
                            $tarifGroup->getId(),
                            json_encode($row)
                        ));
                    }
                    continue;
                }

                // Validate relations via repositories (manufacturer and quality must exist)
                $manufacturer = $this->resolveManufacturer($idFabriquant, $row);
                $quality = $this->resolveQuality($idQualite, $row);

                if (!$manufacturer || !$quality) {
                    $skippedCount++;
                    if ($io->isVerbose()) {
                        $io->writeln(sprintf(
                            '  Skipped row %d: Missing relation(s) - manufacturer id/code: %s (found: %s), quality id/code: %s (found: %s) - row: %s',
                            $index + 2,
                            $idFabriquant,
                            $manufacturer ? 'yes' : 'NO',
                            $idQualite,
                            $quality ? 'yes' : 'NO',
                            json_encode($row)
                        ));
                    }
                    continue;
                }

                // Create and persist
                $priceGrid = new ManufacturerPriceGrid();
                $priceGrid->setManufacturer($manufacturer);
                $priceGrid->setQuality($quality);
                $priceGrid->setTarifGroup($tarifGroup);
                $priceGrid->setTariffGrid($row[4] ?? null);
                $priceGrid->setKnots(isset($row[5]) && $row[5] !== '' ? (int)$row[5] : null);
                $priceGrid->setSpecial($row[6] ?? null);
                $priceGrid->setStandardVelours($row[7] ?? null);
                $priceGrid->setIsActive(true);

                foreach ($materialPrices as $materialReference => $price) {
                    $this->syncManufacturerPrice($priceGrid, $price, $materialReference);
                }

                $this->ensureManufacturerPricesForAllMaterials($priceGrid);


                $this->entityManager->persist($priceGrid);
                $importedCount++;

                // Batch flush
                if ($importedCount % self::BATCH_SIZE === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();

                    if ($io->isVerbose()) {
                        $io->writeln(sprintf('  Processed batch of %d entries...', self::BATCH_SIZE));
                    }
                }
            } catch (\Throwable $e) {
                $skippedCount++;
                $io->warning(sprintf(
                    'Error processing row %d: %s',
                    $index + 2,
                    $e->getMessage()
                ));
            }
        }

        $io->progressFinish();

        // Final flush
        $this->entityManager->flush();

        return [
            'imported' => $importedCount,
            'skipped' => $skippedCount,
            'total' => $totalRows,
        ];
    }

    /**
     * Try resolving a Manufacturer by id or by common external fields if id lookup fails.
     */
    private function resolveManufacturer(int|string $identifier, array $row)
    {
        if ($identifier > 0) {
            $m = $this->manufacturerRepository->find((int)$identifier);
            if ($m) {
                return $m;
            }
        }

        // fallback: try common fields on manufacturer table
        $candidates = ['code', 'reference', 'external_id', 'legacy_id'];
        foreach ($candidates as $field) {
            $found = $this->manufacturerRepository->findOneBy([$field => (string)$identifier]);
            if ($found) {
                return $found;
            }
        }

        // also try raw values from row[1] if different
        $raw = (string)($row[1] ?? '');
        foreach ($candidates as $field) {
            if ($raw === '') {
                continue;
            }
            $found = $this->manufacturerRepository->findOneBy([$field => $raw]);
            if ($found) {
                return $found;
            }
        }

        return null;
    }

    /**
     * Try resolving a Quality by id or by common external fields if id lookup fails.
     */
    private function resolveQuality(int|string $identifier, array $row)
    {
        if ($identifier > 0) {
            $q = $this->qualityRepository->find((int)$identifier);
            if ($q) {
                return $q;
            }
        }

        $candidates = ['code', 'reference', 'external_id', 'legacy_id'];
        foreach ($candidates as $field) {
            $found = $this->qualityRepository->findOneBy([$field => (string)$identifier]);
            if ($found) {
                return $found;
            }
        }

        $raw = (string)($row[2] ?? '');
        foreach ($candidates as $field) {
            if ($raw === '') {
                continue;
            }
            $found = $this->qualityRepository->findOneBy([$field => $raw]);
            if ($found) {
                return $found;
            }
        }

        return null;
    }

    private function extractMaterialPrices(array $row): array
    {
        $prices = [];

        foreach (self::MATERIAL_COLUMN_MAPPING as $materialReference => $columnIndex) {
            $prices[$materialReference] = isset($row[$columnIndex]) && $row[$columnIndex] !== ''
                ? (float)$row[$columnIndex]
                : 0.0;
        }

        return $prices;
    }

    private function syncManufacturerPrice(ManufacturerPriceGrid $priceGrid, float $price, string $materialReference): void
    {

        $material = $this->materialRepository->findOneBy(['reference' => $materialReference]);

        if (!$material) {
            throw new \RuntimeException(sprintf('Material "%s" not found for manufacturer price import.', $materialReference));
        }

        $effectiveDate = new \DateTimeImmutable(sprintf('%d-01-01', (int)$priceGrid->getTarifGroup()?->getYear()));

        // First check in-memory collection to avoid duplicates when the grid hasn't been flushed yet
        $manufacturerPrice = $this->findManufacturerPriceInGrid($priceGrid, $material)
            ?? $this->manufacturerPriceRepository->findOneByGridAndMaterial($priceGrid, $material)
            ?? $this->manufacturerPriceRepository->create([
                'priceGrid' => $priceGrid,
                'material' => $material,
                'effectiveDate' => $effectiveDate,
            ]);

        $manufacturerPrice->setPrice($price);
        $manufacturerPrice->setEffectiveDate($effectiveDate);

        $priceGrid->addManufacturerPrice($manufacturerPrice);
    }

    private function ensureManufacturerPricesForAllMaterials(ManufacturerPriceGrid $priceGrid): void
    {
        $effectiveDate = new \DateTimeImmutable(sprintf('%d-01-01', (int)$priceGrid->getTarifGroup()?->getYear()));

        foreach ($this->materialRepository->findAll() as $material) {
            // check in-memory collection first to avoid duplicates
            $existingPrice = $this->findManufacturerPriceInGrid($priceGrid, $material)
                ?? $this->manufacturerPriceRepository->findOneByGridAndMaterial($priceGrid, $material);

            if (!$existingPrice) {
                $manufacturerPrice = $this->manufacturerPriceRepository->create([
                    'priceGrid' => $priceGrid,
                    'material' => $material,
                    'price' => '0.00',
                    'effectiveDate' => $effectiveDate,
                ]);

                $priceGrid->addManufacturerPrice($manufacturerPrice);
            }
        }
    }

    /**
     * Look for a ManufacturerPrice in the given grid's in-memory collection by material.
     */
    private function findManufacturerPriceInGrid(ManufacturerPriceGrid $priceGrid, $material)
    {
        foreach ($priceGrid->getManufacturerPrices() as $manufacturerPrice) {
            if ($manufacturerPrice->getMaterial()?->getId() === $material->getId()) {
                return $manufacturerPrice;
            }
        }

        return null;
    }
}
