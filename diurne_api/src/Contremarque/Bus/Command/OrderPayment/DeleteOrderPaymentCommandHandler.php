<?php

namespace App\Contremarque\Bus\Command\OrderPayment;

use InvalidArgumentException;
use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\OrderPaymentDetailRepository;
use App\Contremarque\Repository\OrderPaymentRepository;

class DeleteOrderPaymentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly OrderPaymentRepository       $orderPaymentRepository,
        private readonly OrderPaymentDetailRepository $orderPaymentDetailRepository
    )
    {
    }

    public function __invoke(DeleteOrderPaymentCommand $command): OrderPaymentResponse
    {
        $orderPayment = $this->orderPaymentRepository->find($command->id);
        if (null === $orderPayment) {
            throw new InvalidArgumentException('Order payment not found');
        }
        if ($orderPayment->isDeleted()) {
            throw new InvalidArgumentException('Order payment already deleted');
        }
        $this->orderPaymentDetailRepository->softDeleteByOrderPayment($orderPayment->getId());
        $orderPayment->setDeleted(true);
        $orderPayment->setDeletedAt(new DateTime());
        $this->orderPaymentRepository->persist($orderPayment);
        $this->orderPaymentRepository->flush();

        return new OrderPaymentResponse($orderPayment);
    }
}