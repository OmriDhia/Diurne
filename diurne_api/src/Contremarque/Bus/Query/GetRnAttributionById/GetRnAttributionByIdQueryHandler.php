<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetRnAttributionById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\CarpetOrderDetailRepository;
use App\Contremarque\Repository\RnAttributionRepository;

use RuntimeException;

class GetRnAttributionByIdQueryHandler implements QueryHandler
{
    /**
     * @param RnAttributionRepository $rnAttributionRepository
     * @param CarpetOrderDetailRepository $carpetOrderDetailRepository
     */
    public function __construct(
        private readonly RnAttributionRepository     $rnAttributionRepository,
        private readonly CarpetOrderDetailRepository $carpetOrderDetailRepository,
    )
    {
    }

    /**
     * @param GetRnAttributionByIdQuery $query
     * @return GetRnAttributionByIdResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetRnAttributionByIdQuery $query): GetRnAttributionByIdResponse
    {
        try {
            $carpetOrderDetail = $this->carpetOrderDetailRepository->find($query->getId());
            if ($carpetOrderDetail === null) {
                throw new ResourceNotFoundException();
            }
            $rnAttribution = $this->rnAttributionRepository->findOneBy(
                ['carpetOrderDetail' => $carpetOrderDetail, 'canceledAt' => null]);
            $lastCanceled = $this->rnAttributionRepository->findLastCanceledByCarpetOrderDetail($carpetOrderDetail);
            /*if (!$rnAttribution) {
                throw new ResourceNotFoundException(
                    sprintf('RnAttribution with id %d not found', $query->id)
                );
            }*/
        } catch (\Exception $e) {
            throw new RuntimeException('Failed to fetch carpet order: ' . $e->getMessage());
        }
        return new GetRnAttributionByIdResponse($rnAttribution, $lastCanceled);
    }
}