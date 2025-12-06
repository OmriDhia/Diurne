<?php

namespace App\Setting\Bus\Query\PaymentType;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\PaymentTypeRepository;

class GetAllPaymentTypeQueryHandler implements QueryHandler
{
    public function __construct(private readonly PaymentTypeRepository $paymentTypeRepository)
    {
    }

    public function __invoke(GetAllPaymentTypeQuery $query): PaymentTypeQueryResponse
    {
        $allPaymentType = $this->paymentTypeRepository->findAll();

        return new PaymentTypeQueryResponse($allPaymentType);
    }
}
