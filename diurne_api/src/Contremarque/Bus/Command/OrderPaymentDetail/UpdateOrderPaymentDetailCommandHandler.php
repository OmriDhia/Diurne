<?php

namespace App\Contremarque\Bus\Command\OrderPaymentDetail;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\OrderPaymentDetailRepository;
use App\Contremarque\Repository\OrderPaymentRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Invoices\Repository\CustomerInvoiceDetailRepository;
use App\Invoices\Repository\CustomerInvoiceRepository;

class UpdateOrderPaymentDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly OrderPaymentRepository $orderPaymentRepository,
        private readonly QuoteRepository $quoteRepository,
        private readonly QuoteDetailRepository $quoteDetailRepository,
        private readonly CustomerInvoiceRepository $customerInvoiceRepository,
        private readonly CustomerInvoiceDetailRepository $customerInvoiceDetailRepository,
        private readonly OrderPaymentDetailRepository $orderPaymentDetailRepository,
    ) {
    }

    public function __invoke(UpdateOrderPaymentDetailCommand $command): OrderPaymentDetailResponse
    {
        $orderPaymentDetail = $this->orderPaymentDetailRepository->find($command->id);
        if (null === $orderPaymentDetail) {
            throw new InvalidArgumentException('Order payment detail not found');
        }
        if (!empty($command->orderPaymentId)) {
            $orderPayment = $this->orderPaymentRepository->find($command->orderPaymentId);
            if (null === $orderPayment) {
                throw new InvalidArgumentException('Order payment not found');
            }
            $orderPaymentDetail->setOrderPayment($orderPayment);
        }

        $quoteProvided = null !== $command->quoteId || null !== $command->quoteDetailId;
        $invoiceProvided = null !== $command->customerInvoiceId || null !== $command->customerInvoiceDetailId;

        if ($quoteProvided && $invoiceProvided) {
            throw new InvalidArgumentException('Cannot link both a quote and an invoice');
        }

        if (null !== $command->quoteId) {
            $quote = $this->quoteRepository->find($command->quoteId);
            if (null === $quote) {
                throw new InvalidArgumentException('Quote not found');
            }
            $orderPaymentDetail->setQuote($quote);
        }

        if (null !== $command->quoteDetailId) {
            $quoteDetail = $this->quoteDetailRepository->find($command->quoteDetailId);
            if (null === $quoteDetail) {
                throw new InvalidArgumentException('Quote detail not found');
            }
            $orderPaymentDetail->setQuoteDetail($quoteDetail);
        }

        if (null !== $command->customerInvoiceId) {
            $invoice = $this->customerInvoiceRepository->find($command->customerInvoiceId);
            if (null === $invoice) {
                throw new InvalidArgumentException('Customer invoice not found');
            }
            $orderPaymentDetail->setCustomerInvoice($invoice);
        }

        if (null !== $command->customerInvoiceDetailId) {
            $invoiceDetail = $this->customerInvoiceDetailRepository->find($command->customerInvoiceDetailId);
            if (null === $invoiceDetail) {
                throw new InvalidArgumentException('Customer invoice detail not found');
            }
            $orderPaymentDetail->setCustomerInvoiceDetail($invoiceDetail);
        }

        if (!empty($command->commandNumber)) {
            $orderPaymentDetail->setCommandNumber($command->commandNumber);
        }
        if (!empty($command->orderInvoiceId)) {
            $orderPaymentDetail->setOrderInvoiceId($command->orderInvoiceId);
        }
        if (!empty($command->rn)) {
            $orderPaymentDetail->setRn($command->rn);
        }
        if (!empty($command->distribution)) {
            $orderPaymentDetail->setDistribution($command->distribution);
        }
        if (!empty($command->allocatedAmountTtc)) {
            $orderPaymentDetail->setAllocatedAmountTtc($command->allocatedAmountTtc);
        }
        if (!empty($command->remainingAmountTtc)) {
            $orderPaymentDetail->setRemainingAmountTtc($command->remainingAmountTtc);
        }
        if (!empty($command->totalAmountTtc)) {
            $orderPaymentDetail->setTotalAmountTtc($command->totalAmountTtc);
        }
        if (!empty($command->tva)) {
            $orderPaymentDetail->setTva($command->tva);
        }
        if (!empty($command->allocatedAmountHt)) {
            $orderPaymentDetail->setAllocatedAmountHt($command->allocatedAmountHt);
        }
        if (!empty($command->cleared)) {
            $orderPaymentDetail->setCleared($command->cleared);
        }

        $this->orderPaymentDetailRepository->persist($orderPaymentDetail);
        $this->orderPaymentDetailRepository->flush();

        return new OrderPaymentDetailResponse($orderPaymentDetail);
    }
}
