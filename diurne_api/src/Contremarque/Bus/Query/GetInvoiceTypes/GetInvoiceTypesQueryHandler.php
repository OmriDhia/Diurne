<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetInvoiceTypes;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\InvoiceTypeRepository;

class GetInvoiceTypesQueryHandler implements QueryHandler
{
    /**
     * @param InvoiceTypeRepository $repository
     */
    public function __construct(private readonly InvoiceTypeRepository $repository)
    {
    }

    /**
     * @param GetInvoiceTypesQuery $query
     * @return GetInvoiceTypesResponse
     */
    public function __invoke(GetInvoiceTypesQuery $query): GetInvoiceTypesResponse
    {
        $list = $this->repository->findAll();

        return new GetInvoiceTypesResponse($list);
    }
}
