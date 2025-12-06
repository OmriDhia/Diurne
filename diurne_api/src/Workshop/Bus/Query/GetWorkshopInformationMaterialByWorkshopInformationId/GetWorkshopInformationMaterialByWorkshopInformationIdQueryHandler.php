<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationMaterialByWorkshopInformationId;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\WorkshopInformationMaterialRepository;
use App\Workshop\Repository\WorkshopInformationRepository;

class GetWorkshopInformationMaterialByWorkshopInformationIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly WorkshopInformationMaterialRepository $workshopInformationMaterialRepository,
        private readonly WorkshopInformationRepository $workshopInformationRepository,
    ) {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetWorkshopInformationMaterialByWorkshopInformationIdQuery $query): WorkshopInformationMaterialByWorkshopInformationIdResponse
    {
        $workshopInformation = $this->workshopInformationRepository->find($query->workshopInformationId);

        if (!$workshopInformation) {
            throw new ResourceNotFoundException();
        }

        $materials = $this->workshopInformationMaterialRepository->findByWorkshopInformation($workshopInformation);

        return new WorkshopInformationMaterialByWorkshopInformationIdResponse($materials);
    }
}
