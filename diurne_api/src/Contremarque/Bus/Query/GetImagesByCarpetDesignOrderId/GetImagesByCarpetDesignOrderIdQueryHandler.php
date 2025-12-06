<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetImagesByCarpetDesignOrderId;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Entity\Image;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetImagesByCarpetDesignOrderIdQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly ImageRepository             $imageRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository
    )
    {
    }

    public function __invoke(GetImagesByCarpetDesignOrderIdQuery $query): GetImagesByCarpetDesignOrderIdResponse
    {
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find($query->getCarpetDesignOrderId());
        if (!$carpetDesignOrder) {
            return new GetImagesByCarpetDesignOrderIdResponse([], 0, $query->getPage(), $query->getItemsPerPage() ?? 0);
        }

        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $query->getItemsPerPage();

        if ($getAll) {
            $cacheKey = 'images_by_carpet_design_order_' . $query->getCarpetDesignOrderId();

            $this->clearCache($cacheKey);

            $imagesData = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapImages($carpetDesignOrder),
                3600
            );
            $totalItems = count($imagesData);
        } else {
            $imagesData = $this->imageRepository->findBy(
                ['carpetDesignOrder' => $carpetDesignOrder],
                null,
                $limit,
                $offset
            );
            $totalItems = $this->imageRepository->count(['carpetDesignOrder' => $carpetDesignOrder]);
        }

        return new GetImagesByCarpetDesignOrderIdResponse(
            $imagesData,
            $totalItems,
            $query->getPage(),
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * Fetches Image entities and maps them to an array.
     *
     * @param mixed $carpetDesignOrder
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapImages($carpetDesignOrder): array
    {

        $images = $this->imageRepository->findBy(['carpetDesignOrder' => $carpetDesignOrder]);
        return array_map(
            fn(Image $image) => $image->toArray(),
            $images
        );
    }
}
