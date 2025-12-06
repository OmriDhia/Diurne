<?php

namespace App\Contremarque\Bus\Query\TechnicalImage;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\TechnicalImageRepository;

class GetTechnicalImageQueryHandler implements QueryHandler
{
    public function __construct(private readonly TechnicalImageRepository $technicalImageRepository)
    {
    }

    public function __invoke(GetTechnicalImageQuery $query): GetTechnicalImageResponse
    {
        $technicalImage = $this->technicalImageRepository->findAll();
        $formattedTechnicalImage = [];
        foreach ($technicalImage as $image) {
            $formattedTechnicalImage[] = [
                'id' => $image->getId(),
                'name' => $image->getName(),
                'image_command' => $image->getImageCommand()->getId(),
                'image_type' => $image->getImageType()->getId(),
                'attachment_id' => $image->getAttachment()?->getId(),
                'createdAt' => $image->getCreatedAt(),
                'updatedAt' => $image->getUpdatedAt(),
            ];
        }

        return new GetTechnicalImageResponse($formattedTechnicalImage);
    }
}
