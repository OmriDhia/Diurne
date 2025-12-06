<?php

namespace App\Contremarque\Bus\Query\GetOrderPaymentById;

use InvalidArgumentException;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\OrderPaymentRepository;

class GetOrderPaymentByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly OrderPaymentRepository $orderPaymentRepository
    )
    {
    }

    public function __invoke(GetOrderPaymentByIdQuery $query): GetOrderPaymentByIdQueryResponse
    {
        $orderPayments = $this->orderPaymentRepository->find($query->getOrderId());
        if (empty($orderPayments)) {
            throw new InvalidArgumentException('No order payments found');
        }
        
        return new GetOrderPaymentByIdQueryResponse($orderPayments);

    }
}