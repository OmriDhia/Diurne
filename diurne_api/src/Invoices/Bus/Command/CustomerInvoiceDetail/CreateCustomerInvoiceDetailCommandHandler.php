<?php

namespace App\Invoices\Bus\Command\CustomerInvoiceDetail;

use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Repository\CustomerInvoiceDetailRepository;
use App\Invoices\Repository\CustomerInvoiceRepository;
use App\Contremarque\Repository\CarpetOrderDetailRepository;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\ModelRepository;
use InvalidArgumentException;

class CreateCustomerInvoiceDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CustomerInvoiceRepository $invoiceRepository,
        private readonly CarpetOrderDetailRepository $carpetOrderDetailRepository,
        private readonly CustomerInvoiceDetailRepository $detailRepository,
        private readonly CarpetCollectionRepository $collectionRepository,
        private readonly ModelRepository $modelRepository
    ) {
    }

    public function __invoke(CreateCustomerInvoiceDetailCommand $command): CustomerInvoiceDetailResponse
    {
        $invoice = $this->invoiceRepository->find($command->getCustomerInvoiceId());
        if (null === $invoice) {
            throw new InvalidArgumentException('Customer invoice not found');
        }
        $orderDetail = $this->carpetOrderDetailRepository->find($command->getCarpetOrderDetailId());
        if (null === $orderDetail) {
            throw new InvalidArgumentException('Carpet order detail not found');
        }

        $collection = null;
        if (null !== $command->getCollectionId()) {
            $collection = $this->collectionRepository->find($command->getCollectionId());
        }

        $model = null;
        if (null !== $command->getModelId()) {
            $model = $this->modelRepository->find($command->getModelId());
        }

        $detail = new \App\Invoices\Entity\CustomerInvoiceDetail();
        $detail->setCustomerInvoice($invoice)
            ->setCarpetOrderDetail($orderDetail)
            ->setCleared($command->isCleared())
            ->setRefCommand($command->getRefCommand())
            ->setRefQuote($command->getRefQuote())
            ->setRn($command->getRn())
            ->setCollection($collection)
            ->setModel($model)
            ->setM2($command->getM2())
            ->setSqft($command->getSqft())
            ->setHt($command->getHt())
            ->setTtc($command->getTtc());

        $this->detailRepository->persist($detail);
        $this->detailRepository->flush();

        return new CustomerInvoiceDetailResponse($detail);
    }
}
