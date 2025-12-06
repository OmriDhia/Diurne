<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DesignerComposition;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Repository\DesignerCompositionRepository;
use App\Setting\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateDesignerCompositionCommandHandler implements CommandHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly DesignerCompositionRepository $designerCompositionRepository, private readonly MaterialRepository $materialRepository, private readonly CarpetSpecificationRepository $carpetSpecificationRepository)
    {
    }

    public function __invoke(UpdateDesignerCompositionCommand $command): UpdateDesignerCompositionResponse
    {
        $designerComposition = $this->designerCompositionRepository->find($command->id);

        if (!$designerComposition) {
            throw new Exception('DesignerComposition not found');
        }

        if ($command->materialId) {
            $material = $this->materialRepository->find($command->materialId);
            $designerComposition->setMaterial($material);
        }

        if ($command->rate) {
            $designerComposition->setRate($command->rate);
        }

        $this->entityManager->persist($designerComposition);
        $this->entityManager->flush();

        return new UpdateDesignerCompositionResponse($designerComposition);
    }
}
