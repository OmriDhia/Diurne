<?php

namespace App\Invoices\Bus\Query\SupplierInvoiceDetail;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Invoices\Repository\SupplierInvoiceDetailRepository;

class GetSupplierInvoiceDetailByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly SupplierInvoiceDetailRepository $repository)
    {
    }

    public function __invoke(GetSupplierInvoiceDetailByIdQuery $query): GetSupplierInvoiceDetailByIdResponse
    {
        $detail = $this->repository->find($query->id);
        if (null === $detail) {
            throw new ResourceNotFoundException();
        }

        return new GetSupplierInvoiceDetailByIdResponse($detail);
    }
}
