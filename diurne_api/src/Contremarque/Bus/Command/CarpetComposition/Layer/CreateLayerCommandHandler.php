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

class CreateLayerCommandHandler implements CommandHandler
{
    public function __construct(private readonly LayerRepository $layerRepository, private readonly CarpetCompositionRepository $carpetCompositionRepository, private readonly EntityManagerInterface $entityManager, private readonly ColorRepository $colorRepository, private readonly MaterialRepository $materialRepository, private readonly ThreadRepository $threadRepository)
    {
    }

    public function __invoke(CreateLayerCommand $command): CreateLayerResponse
    {
        $carpetComposition = $this->carpetCompositionRepository->find($command->carpetCompositionId);

        if (!$carpetComposition) {
            throw new Exception('Carpet composition not found');
        }

        $layer = new Layer();
        $layer->setCarpetComposition($carpetComposition);
        $layer->setLayerNumber($command->layerNumber);
        $layer->setRemarque($command->remarque);
        $this->entityManager->persist($layer);
        if (!empty($command->layerDetails)) {
            foreach ($command->layerDetails as $detailDto) {
                $layerDetail = new LayerDetail();
                $layerDetail->setLayer($layer);
                $thread = $this->threadRepository->find((int) $detailDto->threadId);

                if (!$thread) {
                    continue;
                }
                $layerDetail->setThread($thread);
                if (!empty($detailDto->color_id)) {
                    $color = $this->colorRepository->find((int) $detailDto->color_id);
                    if (!$color) {
                        continue;
                    }
                    $layerDetail->setColor($color);
                }
                if (!empty($detailDto->material_id)) {
                    $material = $this->materialRepository->find((int) $detailDto->material_id);
                    if (!$material) {
                        continue;
                    }
                    $layerDetail->setMaterial($material);
                }

                $layerDetail->setPercentage((string) $detailDto->pourcentage);
                $this->entityManager->persist($layerDetail);
                $this->entityManager->flush();

                $layer->addLayerDetail($layerDetail);
            }
        }

        $this->entityManager->flush();

        return new CreateLayerResponse($layer);
    }
}
