<?php

namespace App\Setting\Bus\Command\PaymentType;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Repository\PaymentTypeRepository;
use Doctrine\ORM\EntityNotFoundException;

class DeletePaymentTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly PaymentTypeRepository $paymentTypeRepository)
    {
    }

    public function __invoke(DeletePaymentTypeCommand $command): void
    {
        $paymentType = $this->paymentTypeRepository->find($command->getId());
        if (!$paymentType) {
            throw new EntityNotFoundException('PaymentType not found');
        }
        $this->paymentTypeRepository->remove($paymentType);
        $this->paymentTypeRepository->flush();
    }
}