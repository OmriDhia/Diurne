<?php

namespace App\Invoices\Bus\Command\SupplierInvoiceDetail;

use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Repository\SupplierInvoiceDetailRepository;
use InvalidArgumentException;

class DeleteSupplierInvoiceDetailCommandHandler implements CommandHandler
{
    public function __construct(private readonly SupplierInvoiceDetailRepository $detailRepository)
    {
    }

    public function __invoke(DeleteSupplierInvoiceDetailCommand $command): SupplierInvoiceDetailResponse
    {
        $detail = $this->detailRepository->find($command->id);
        if (null === $detail) {
            throw new InvalidArgumentException('Supplier invoice detail not found');
        }

        $this->detailRepository->remove($detail);
        return new SupplierInvoiceDetailResponse($detail);
    }
}
