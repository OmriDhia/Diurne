<?php

namespace App\Invoices\Bus\Query\CustomerInvoice;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Invoices\Repository\CustomerInvoiceRepository;

final readonly class GetCustomerInvoiceByIdQueryHandler implements QueryHandler
{
    public function __construct(private CustomerInvoiceRepository $repository)
    {
    }

    public function __invoke(GetCustomerInvoiceByIdQuery $query): GetCustomerInvoiceByIdResponse
    {
        $invoice = $this->repository->find($query->id);
        if (null === $invoice) {
            throw new ResourceNotFoundException();
        }

        return new GetCustomerInvoiceByIdResponse($invoice);
    }
}
