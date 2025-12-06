<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrderById;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CustomerInstructionRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetCarpetDesignOrderByIdQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(
        private readonly CarpetDesignOrderRepository   $carpetDesignOrderRepository,
        private readonly CustomerInstructionRepository $customerInstructionRepository,
        private readonly ImageCommandRepository        $imageCommandRepository,
        private readonly EntityManagerInterface        $entityManager
    )
    {
    }

    public function __invoke(GetCarpetDesignOrderByIdQuery $query): CarpetDesignOrderQueryResponse
    {
        $cacheKey = 'carpet_design_order_' . $query->getId();

        if ($query->isForceRefresh() || 1 == 1) {
            $this->clearCache($cacheKey);
        }

        $carpetDesignOrderData = $this->getCachedResult(
            $cacheKey,
            function () use ($query) {
                $carpetDesignOrder = $this->carpetDesignOrderRepository->find($query->getId());
                if (!$carpetDesignOrder) {
                    throw new ResourceNotFoundException();
                }
                return $this->mapCarpetDesignOrderToArray($carpetDesignOrder);
            },
            3600 // Cache for 1 hour
        );

        return new CarpetDesignOrderQueryResponse($carpetDesignOrderData);
    }

    /**
     * Maps a CarpetDesignOrder entity to an array for caching.
     *
     * @param CarpetDesignOrder $carpetDesignOrder
     * @return array<string, mixed>
     */
    private function mapCarpetDesignOrderToArray(CarpetDesignOrder $carpetDesignOrder): array
    {
        $designerData = [];
        foreach ($carpetDesignOrder->getDesigners() as $designer) {
            $designerData[] = $designer->toArray();
        }
        $hasImageCommand = false;
        $imageCommandIsCanceled = false;
        foreach ($carpetDesignOrder->getImageCommands() as $imageCommand) {
            $hasImageCommand = true;
            if ($imageCommand->getCanceledAt() !== null && $imageCommand->getCanceledBy() !== null) {
                $imageCommandIsCanceled = true;
                break;
            }
        }
        return [
            'id' => $carpetDesignOrder->getId(),
            'projectDi' => $carpetDesignOrder->getProjectDi() ? $carpetDesignOrder->getProjectDi()->getId() : null,
            'location' => $carpetDesignOrder->getLocation() ? $carpetDesignOrder->getLocation()->toArray() : null,
            'status' => $carpetDesignOrder->getStatus() ? $carpetDesignOrder->getStatus()->toArray() : null,
            'designers' => $designerData,
            'carpetSpecification' => $carpetDesignOrder->getCarpetSpecification() ? $carpetDesignOrder->getCarpetSpecification()->toArray() : null,
            'customerInstruction' => $carpetDesignOrder->getCustomerInstruction() ? $carpetDesignOrder->getCustomerInstruction()->toArray() : null,
            'variation' => $carpetDesignOrder->getVariation(),
            'variationImageReference' => $carpetDesignOrder->getVariationImageReference(),
            'modelName' => $carpetDesignOrder->getModelName(),
            'jpeg' => $carpetDesignOrder->isJpeg(),
            'impression' => $carpetDesignOrder->isImpression(),
            'impressionBarreDeLaine' => $carpetDesignOrder->isImpressionBarreDeLaine(),
            'transmition_date' => $carpetDesignOrder->getTransmitionDate(),
            'isSampleContainer' => $carpetDesignOrder->isSampleContainer() ? $carpetDesignOrder->isSampleContainer() : false,
            'hasImageCommand' => $hasImageCommand,
            'imageCommandIsCanceled' => $imageCommandIsCanceled,
        ];
    }
}
