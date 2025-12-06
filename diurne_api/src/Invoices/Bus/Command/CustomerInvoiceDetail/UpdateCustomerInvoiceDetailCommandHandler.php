<?php

namespace App\Invoices\Bus\Command\CustomerInvoiceDetail;

use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Repository\CustomerInvoiceDetailRepository;
use App\Invoices\Repository\CustomerInvoiceRepository;
use App\Contremarque\Repository\CarpetOrderDetailRepository;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\ModelRepository;
use InvalidArgumentException;

class UpdateCustomerInvoiceDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CustomerInvoiceRepository $invoiceRepository,
        private readonly CarpetOrderDetailRepository $carpetOrderDetailRepository,
        private readonly CustomerInvoiceDetailRepository $detailRepository,
        private readonly CarpetCollectionRepository $collectionRepository,
        private readonly ModelRepository $modelRepository
    ) {
    }

    public function __invoke(UpdateCustomerInvoiceDetailCommand $command): CustomerInvoiceDetailResponse
    {
        $detail = $this->detailRepository->find($command->id);
        if (null === $detail) {
            throw new InvalidArgumentException('Customer invoice detail not found');
        }

        if (null !== $command->customerInvoiceId) {
            $invoice = $this->invoiceRepository->find($command->customerInvoiceId);
            if (null === $invoice) {
                throw new InvalidArgumentException('Customer invoice not found');
            }
            $detail->setCustomerInvoice($invoice);
        }

        if (null !== $command->carpetOrderDetailId) {
            $orderDetail = $this->carpetOrderDetailRepository->find($command->carpetOrderDetailId);
            if (null === $orderDetail) {
                throw new InvalidArgumentException('Carpet order detail not found');
            }
            $detail->setCarpetOrderDetail($orderDetail);
        }

        if (null !== $command->rn) {
            $detail->setRn($command->rn);
        }

        if (null !== $command->collectionId) {
            $collection = $this->collectionRepository->find($command->collectionId);
            $detail->setCollection($collection);
        }

        if (null !== $command->modelId) {
            $model = $this->modelRepository->find($command->modelId);
            $detail->setModel($model);
        }

        if (null !== $command->m2) {
            $detail->setM2($command->m2);
        }

        if (null !== $command->sqft) {
            $detail->setSqft($command->sqft);
        }

        if (null !== $command->ht) {
            $detail->setHt($command->ht);
        }

        if (null !== $command->ttc) {
            $detail->setTtc($command->ttc);
        }

        if (null !== $command->cleared) {
            $detail->setCleared($command->cleared);
        }

        if (null !== $command->refCommand) {
            $detail->setRefCommand($command->refCommand);
        }

        if (null !== $command->refQuote) {
            $detail->setRefQuote($command->refQuote);
        }

        $this->detailRepository->persist($detail);
        $this->detailRepository->flush();

        return new CustomerInvoiceDetailResponse($detail);
    }
}
