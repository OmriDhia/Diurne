<?php

namespace App\Invoices\Bus\Query\CustomerInvoiceDetail;

use App\Common\Bus\Query\QueryHandler;
use App\Invoices\Repository\CustomerInvoiceDetailRepository;
use App\Common\Exception\ResourceNotFoundException;

class GetCustomerInvoiceDetailByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly CustomerInvoiceDetailRepository $repository)
    {
    }

    public function __invoke(GetCustomerInvoiceDetailByIdQuery $query): GetCustomerInvoiceDetailByIdResponse
    {
        $detail = $this->repository->find($query->id);
        if (null === $detail) {
            throw new ResourceNotFoundException();
        }

        return new GetCustomerInvoiceDetailByIdResponse($detail);
    }
}
