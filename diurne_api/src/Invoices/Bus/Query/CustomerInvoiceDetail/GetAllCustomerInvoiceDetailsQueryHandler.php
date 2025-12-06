<?php

namespace App\Invoices\Bus\Query\CustomerInvoiceDetail;

use App\Common\Bus\Query\QueryHandler;
use App\Invoices\Repository\CustomerInvoiceDetailRepository;
use InvalidArgumentException;

class GetAllCustomerInvoiceDetailsQueryHandler implements QueryHandler
{
    public function __construct(private readonly CustomerInvoiceDetailRepository $repository)
    {
    }

    public function __invoke(GetAllCustomerInvoiceDetailsQuery $query): GetAllCustomerInvoiceDetailsResponse
    {
        $details = $this->repository->findAll();
        if (empty($details)) {
            throw new InvalidArgumentException('No customer invoice details found');
        }

        $formatted = [];
        foreach ($details as $detail) {
            if (!$detail->isDeleted()) {
                $formatted[] = $detail->toArray();
            }
        }

        return new GetAllCustomerInvoiceDetailsResponse($formatted);
    }
}
