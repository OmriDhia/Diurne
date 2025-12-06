<?php

namespace App\Contremarque\Bus\Command\OrderPaymentDetail;

use InvalidArgumentException;
use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\OrderPaymentDetailRepository;

class DeleteOrderPaymentDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly OrderPaymentDetailRepository $orderPaymentDetailRepository
    )
    {
    }

    public function __invoke(DeleteOrderPaymentDetailCommand $command): OrderPaymentDetailResponse
    {
        $orderPaymentDetail = $this->orderPaymentDetailRepository->find($command->orderPaymentDetailId);
        if (null === $orderPaymentDetail) {
            throw new InvalidArgumentException('Order payment detail not found');
        }
        if ($orderPaymentDetail->isDeleted()) {
            throw new InvalidArgumentException('Order payment detail already deleted');
        }
        $orderPaymentDetail->setDeleted(true);
        $orderPaymentDetail->setDeletedAt(new DateTime());
        $this->orderPaymentDetailRepository->persist($orderPaymentDetail);
        $this->orderPaymentDetailRepository->flush();

        return new OrderPaymentDetailResponse($orderPaymentDetail);
    }
}