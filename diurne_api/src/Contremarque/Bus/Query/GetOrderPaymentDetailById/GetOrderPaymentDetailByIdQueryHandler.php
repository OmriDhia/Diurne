<?php

namespace App\Contremarque\Bus\Query\GetOrderPaymentDetailById;

use InvalidArgumentException;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\OrderPaymentDetailRepository;

class GetOrderPaymentDetailByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly OrderPaymentDetailRepository $orderPaymentDetailRepository
    )
    {
    }

    public function __invoke(GetOrderPaymentDetailByIdQuery $query): GetOrderPaymentDetailByIdQueryResponse
    {
        $orderPayments = $this->orderPaymentDetailRepository->find($query->getOrderDetailId());
        if (empty($orderPayments)) {
            throw new InvalidArgumentException('No order payments found');
        }
        
        return new GetOrderPaymentDetailByIdQueryResponse($orderPayments);

    }
}