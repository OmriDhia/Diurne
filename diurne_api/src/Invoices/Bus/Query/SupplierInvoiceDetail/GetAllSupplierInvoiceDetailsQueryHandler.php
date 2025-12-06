<?php

namespace App\Invoices\Bus\Query\SupplierInvoiceDetail;

use App\Common\Bus\Query\QueryHandler;
use App\Invoices\Repository\SupplierInvoiceDetailRepository;
use InvalidArgumentException;

class GetAllSupplierInvoiceDetailsQueryHandler implements QueryHandler
{
    public function __construct(private readonly SupplierInvoiceDetailRepository $repository)
    {
    }

    public function __invoke(GetAllSupplierInvoiceDetailsQuery $query): GetAllSupplierInvoiceDetailsResponse
    {
        $details = $this->repository->findAll();
        if (empty($details)) {
            throw new InvalidArgumentException('No supplier invoice details found');
        }

        $formatted = [];
        foreach ($details as $detail) {
            $formatted[] = $detail->toArray();
        }

        return new GetAllSupplierInvoiceDetailsResponse($formatted);
    }
}
