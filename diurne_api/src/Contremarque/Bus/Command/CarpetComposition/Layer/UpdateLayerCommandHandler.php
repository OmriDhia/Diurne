<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetComposition\Layer;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\Layer;
use App\Contremarque\Entity\LayerDetail;
use App\Contremarque\Repository\CarpetCompositionRepository;
use App\Contremarque\Repository\LayerRepository;
use App\Contremarque\Repository\ThreadRepository;
use App\Setting\Repository\ColorRepository;
use App\Setting\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateLayerCommandHandler implements CommandHandler
{
    public function __construct(private readonly LayerRepository $layerRepository, private readonly CarpetCompositionRepository $carpetCompositionRepository, private readonly EntityManagerInterface $entityManager, private readonly ColorRepository $colorRepository, private readonly MaterialRepository $materialRepository, private readonly ThreadRepository $threadRepository)
    {
    }

    public function __invoke(UpdateLayerCommand $command): UpdateLayerResponse
    {
        $layer = $this->layerRepository->find($command->layerId);
        if (!$layer) {
            throw new Exception('Layer not found');
        }

        // Update layer number and remarque if they are provided and not empty
        if (!empty($command->layerNumber)) {
            $layer->setLayerNumber($command->layerNumber);
        }

        if (!empty($command->remarque)) {
            $layer->setRemarque($command->remarque);
        }

        if (!empty($command->layerDetails)) {
            // Remove old LayerDetail entries
            foreach ($layer->getLayerDetails() as $existingDetail) {
                $this->entityManager->remove($existingDetail);
                $this->entityManager->flush();
            }
        }

        // Update layer details if provided
        if (!empty($command->layerDetails)) {
            foreach ($command->layerDetails as $detailDto) {
                $layerDetail = new LayerDetail();
                $layerDetail->setLayer($layer);

                // Check if each detail field is not empty before setting it
                if (!empty($detailDto['threadId'])) {
                    $thread = $this->threadRepository->find((int) $detailDto['threadId']);
                    if ($thread) {
                        $layerDetail->setThread($thread);
                    }
                }

                if (!empty($detailDto['color_id'])) {
                    $color = $this->colorRepository->find((int) $detailDto['color_id']);
                    if ($color) {
                        $layerDetail->setColor($color);
                    }
                }

                if (!empty($detailDto['material_id'])) {
                    $material = $this->materialRepository->find((int) $detailDto['material_id']);
                    if ($material) {
                        $layerDetail->setMaterial($material);
                    }
                }

                if (null !== $detailDto['pourcentage']) {
                    $layerDetail->setPercentage((string) $detailDto['pourcentage']);
                }
                $this->entityManager->persist($layerDetail);
                $layer->addLayerDetail($layerDetail);
            }
        }

        $this->entityManager->persist($layer);
        $this->entityManager->flush();

        return new UpdateLayerResponse($layer);
    }
}
