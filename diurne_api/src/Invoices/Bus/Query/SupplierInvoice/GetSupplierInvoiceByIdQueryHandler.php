<?php

namespace App\Invoices\Bus\Query\SupplierInvoice;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Invoices\Repository\SupplierInvoiceRepository;

final readonly class GetSupplierInvoiceByIdQueryHandler implements QueryHandler
{
    public function __construct(private SupplierInvoiceRepository $repository)
    {
    }

    public function __invoke(GetSupplierInvoiceByIdQuery $query): GetSupplierInvoiceByIdResponse
    {
        $invoice = $this->repository->find($query->id);
        if (null === $invoice) {
            throw new ResourceNotFoundException();
        }

        return new GetSupplierInvoiceByIdResponse($invoice);
    }
}
