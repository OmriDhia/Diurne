<?php

namespace App\Setting\Bus\Command\Material;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\ManufacturerPriceRepository;

class DeleteMaterialCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly MaterialRepository                             $materialRepository,
        private readonly ManufacturerPriceRepository                    $manufacturerPriceRepository,
        private readonly \App\Setting\Repository\MaterialLangRepository $materialLangRepository,
    )
    {
    }

    public function __invoke(DeleteMaterialCommand $command): MaterialResponse
    {
        $material = $this->materialRepository->find($command->id);
        if (!$material) {
            throw new RuntimeException('Material not found', 404);
        }

        try {
            // Remove manufacturer prices linked to this material
            foreach ($this->manufacturerPriceRepository->findBy(['material' => $material]) as $manufacturerPrice) {
                $this->manufacturerPriceRepository->remove($manufacturerPrice);
            }

            // Remove material language entries that reference this material to avoid FK constraint
            foreach ($this->materialLangRepository->findBy(['material' => $material]) as $materialLang) {
                $this->materialLangRepository->remove($materialLang);
            }

            $this->materialRepository->remove($material);
            $this->materialRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete material: ' . $e->getMessage(), 0, $e);
        }

        return new MaterialResponse($material);
    }
}
