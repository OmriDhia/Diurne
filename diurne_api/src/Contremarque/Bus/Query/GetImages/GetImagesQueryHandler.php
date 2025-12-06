<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetImages;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ImageRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class GetImagesQueryHandler implements QueryHandler
{
    public function __construct(private ImageRepository $imageRepository) {}

    public function __invoke(GetImagesQuery $query): GetImagesQueryResponse
    {
        $images = $this->imageRepository->findByFilters($query->dto);

        if (empty($images)) {
            return new GetImagesQueryResponse([]);
        }

        return new GetImagesQueryResponse($images);
    }
}
