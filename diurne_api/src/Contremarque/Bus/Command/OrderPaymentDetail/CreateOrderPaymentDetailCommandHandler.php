<?php

namespace App\Contremarque\Bus\Command\OrderPaymentDetail;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\OrderPayment\OrderPaymentDetail;
use App\Contremarque\Repository\OrderPaymentDetailRepository;
use App\Contremarque\Repository\OrderPaymentRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Invoices\Repository\CustomerInvoiceDetailRepository;
use App\Invoices\Repository\CustomerInvoiceRepository;

class CreateOrderPaymentDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly OrderPaymentRepository       $orderPaymentRepository,
        private readonly QuoteRepository              $quoteRepository,
        private readonly QuoteDetailRepository        $quoteDetailRepository,
        private readonly CustomerInvoiceRepository   $customerInvoiceRepository,
        private readonly CustomerInvoiceDetailRepository $customerInvoiceDetailRepository,
        private readonly OrderPaymentDetailRepository $orderPaymentDetailRepository,
    )
    {
    }

    public function __invoke(CreateOrderPaymentDetailCommand $command): OrderPaymentDetailResponse
    {


        $orderPayment = $this->orderPaymentRepository->find($command->getOrderPaymentId());
        if (null === $orderPayment) {
            throw new InvalidArgumentException('Order payment not found');
        }

        $quoteId = $command->getQuoteId();
        $quoteDetailId = $command->getQuoteDetailId();
        $invoiceId = $command->getCustomerInvoiceId();
        $invoiceDetailId = $command->getCustomerInvoiceDetailId();

        if ((null !== $quoteId || null !== $quoteDetailId) && (null !== $invoiceId || null !== $invoiceDetailId)) {
            throw new InvalidArgumentException('Cannot link both a quote and an invoice');
        }

        $quote = null;
        $quoteDetail = null;
        $invoice = null;
        $invoiceDetail = null;

        if (null !== $quoteId || null !== $quoteDetailId) {
            $quote = $this->quoteRepository->find($quoteId);
            if (null === $quote) {
                throw new InvalidArgumentException('Quote not found');
            }

            $quoteDetail = $this->quoteDetailRepository->find($quoteDetailId);
            if (null === $quoteDetail) {
                throw new InvalidArgumentException('Quote detail not found');
            }
        } elseif (null !== $invoiceId || null !== $invoiceDetailId) {
            $invoice = $this->customerInvoiceRepository->find($invoiceId);
            if (null === $invoice) {
                throw new InvalidArgumentException('Customer invoice not found');
            }

            $invoiceDetail = $this->customerInvoiceDetailRepository->find($invoiceDetailId);
            if (null === $invoiceDetail) {
                throw new InvalidArgumentException('Customer invoice detail not found');
            }
        } else {
            throw new InvalidArgumentException('Either quote or invoice data must be provided');
        }


        $orderPaymentDetail = new OrderPaymentDetail();
        $orderPaymentDetail->setOrderPayment($orderPayment);

        if (null !== $quote) {
            $orderPaymentDetail->setQuote($quote);
            $orderPaymentDetail->setQuoteDetail($quoteDetail);
        } else {
            $orderPaymentDetail->setCustomerInvoice($invoice);
            $orderPaymentDetail->setCustomerInvoiceDetail($invoiceDetail);
        }
        $orderPaymentDetail->setCommandNumber($command->getCommandNumber());
        $orderPaymentDetail->setOrderInvoiceId($command->getOrderInvoiceId());
        $orderPaymentDetail->setRn($command->getRn());
        $orderPaymentDetail->setDistribution($command->getDistribution());
        $orderPaymentDetail->setAllocatedAmountTtc($command->getAllocatedAmountTtc());
        $orderPaymentDetail->setRemainingAmountTtc($command->getRemainingAmountTtc());
        $orderPaymentDetail->setTotalAmountTtc($command->getTotalAmountTtc());
        $orderPaymentDetail->setTva($command->getTva());
        $orderPaymentDetail->setAllocatedAmountHt($command->getAllocatedAmountHt());
        $orderPaymentDetail->setCleared($command->isCleared());

        $this->orderPaymentDetailRepository->persist($orderPaymentDetail);
        $this->orderPaymentDetailRepository->flush();

        return new OrderPaymentDetailResponse($orderPaymentDetail);
    }
}
