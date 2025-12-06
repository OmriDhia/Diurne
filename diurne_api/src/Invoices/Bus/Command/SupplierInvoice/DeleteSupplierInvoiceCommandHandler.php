<?php

namespace App\Invoices\Bus\Command\SupplierInvoice;

use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Repository\SupplierInvoiceRepository;
use App\Common\Exception\ResourceNotFoundException;

class DeleteSupplierInvoiceCommandHandler implements CommandHandler
{
    public function __construct(private readonly SupplierInvoiceRepository $repository)
    {
    }

    public function __invoke(DeleteSupplierInvoiceCommand $command): SupplierInvoiceResponse
    {
        $invoice = $this->repository->find($command->id);
        if (!$invoice) {
            throw new ResourceNotFoundException();
        }
        $this->repository->remove($invoice);
        return new SupplierInvoiceResponse($invoice);
    }
}
