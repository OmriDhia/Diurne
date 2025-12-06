<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\CheckWorkshopImageCommandCoherence;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Bus\Query\CheckSpecificationCoherence\CheckSpecificationCoherenceResponse;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use App\Workshop\Service\CheckWorkshopImageCommandCoherence\CheckWorkshopImageCommandCoherence;

final class CheckWorkshopImageCommandCoherenceQueryHandler implements QueryHandler
{
    public function __construct(
        private WorkshopOrderRepository            $workshopOrderRepository,
        private ImageCommandRepository             $imageCommandRepository,
        private CheckWorkshopImageCommandCoherence $coherenceService
    )
    {
    }

    public function __invoke(CheckWorkshopImageCommandCoherenceQuery $query): CheckSpecificationCoherenceResponse
    {
        $workshopOrder = $this->workshopOrderRepository->find($query->getWorkshopOrderId());
        if (!$workshopOrder) {
            throw new ResourceNotFoundException('WorkshopOrder not found');
        }

        $imageCommand = $this->imageCommandRepository->find($query->getImageCommandId());
        if (!$imageCommand) {
            throw new ResourceNotFoundException('ImageCommand not found');
        }

        return $this->coherenceService->check($workshopOrder, $imageCommand);
    }
}
