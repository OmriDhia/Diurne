<?php

namespace App\Invoices\Bus\Command\CustomerInvoiceDetail;

use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Repository\CustomerInvoiceDetailRepository;
use InvalidArgumentException;

class DeleteCustomerInvoiceDetailCommandHandler implements CommandHandler
{
    public function __construct(private readonly CustomerInvoiceDetailRepository $detailRepository)
    {
    }

    public function __invoke(DeleteCustomerInvoiceDetailCommand $command): CustomerInvoiceDetailResponse
    {
        $detail = $this->detailRepository->find($command->id);
        if (null === $detail) {
            throw new InvalidArgumentException('Customer invoice detail not found');
        }
        if ($detail->isDeleted()) {
            throw new InvalidArgumentException('Customer invoice detail already deleted');
        }
        $detail->setDeleted(true);
        $detail->setDeletedAt(new DateTime());

        $this->detailRepository->persist($detail);
        $this->detailRepository->flush();

        return new CustomerInvoiceDetailResponse($detail);
    }
}
