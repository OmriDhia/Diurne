<?php

namespace App\Invoices\Bus\Command\CustomerInvoice;

use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Repository\CustomerInvoiceRepository;
use App\Common\Exception\ResourceNotFoundException;

class DeleteCustomerInvoiceCommandHandler implements CommandHandler
{
    public function __construct(private readonly CustomerInvoiceRepository $repository)
    {
    }

    public function __invoke(DeleteCustomerInvoiceCommand $command): CustomerInvoiceResponse
    {
        $invoice = $this->repository->find($command->id);
        if (!$invoice) {
            throw new ResourceNotFoundException();
        }
        $this->repository->remove($invoice);
        return new CustomerInvoiceResponse($invoice);
    }
}
