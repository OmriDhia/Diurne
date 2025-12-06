<?php

namespace App\Contremarque\Bus\Query\OrderPaimentDetails;

use InvalidArgumentException;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\OrderPaymentDetailRepository;

class GetAllOrderPaymentDetailsQueryHandler implements QueryHandler
{
    public function __construct(private readonly OrderPaymentDetailRepository $orderPaymentDetailRepository)
    {
    }

    public function __invoke(GetAllOrderPaymentDetailsQuery $query): OrderPaymentDetailsQueryResponse
    {
        $orderPaymentDetails = $this->orderPaymentDetailRepository->findAll();
        if (empty($orderPaymentDetails)) {
            throw new InvalidArgumentException('No order payment details found');
        }
        $formattedOrderPayments = [];
        foreach ($orderPaymentDetails as $orderPaymentDetail) {
            if ($orderPaymentDetail->isDeleted() === false) {
                $formattedOrderPayments[] = $orderPaymentDetail->toArray();
            }
        }
        return new OrderPaymentDetailsQueryResponse($formattedOrderPayments);
    }
}