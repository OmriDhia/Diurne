<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateDesignerComposition;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\DesignerComposition;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Repository\DesignerCompositionRepository;
use App\Setting\Repository\MaterialRepository;

class CreateDesignerCompositionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly MaterialRepository $materialRepository,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly DesignerCompositionRepository $designerCompositionRepository
    ) {
    }

    public function __invoke(CreateDesignerCompositionCommand $command): DesignerCompositionResponse
    {
        $material = $this->materialRepository->find($command->getMaterialId());
        if (!$material) {
            throw new ValidationException(['Material not found']);
        }

        $carpetSpecification = $this->carpetSpecificationRepository->find($command->getCarpetSpecificationId());
        if (!$carpetSpecification) {
            throw new ValidationException(['Carpet specification not found']);
        }

        $designerComposition = new DesignerComposition();
        $designerComposition->setMaterial($material);
        $designerComposition->setCarpetSpecification($carpetSpecification);
        $designerComposition->setRate($command->getRate());

        $this->designerCompositionRepository->persist($designerComposition);
        $this->designerCompositionRepository->flush();

        return new DesignerCompositionResponse($designerComposition);
    }
}
