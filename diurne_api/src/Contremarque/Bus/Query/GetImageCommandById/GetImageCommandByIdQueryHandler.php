<?php

namespace App\Contremarque\Bus\Query\GetImageCommandById;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\Sample;
use App\Contremarque\Repository\ImageCommandRepository;
use Doctrine\ORM\EntityManagerInterface;

class GetImageCommandByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ImageCommandRepository $imageCommandRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(GetImageCommandByIdQuery $query): GetImageCommandByIdResponse
    {
        $imageCommand = $this->imageCommandRepository->find((int)$query->getImageCommandId());
        return new GetImageCommandByIdResponse($imageCommand);
    }
}
