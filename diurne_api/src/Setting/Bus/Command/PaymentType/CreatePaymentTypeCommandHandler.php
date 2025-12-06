<?php

namespace App\Setting\Bus\Command\PaymentType;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\PaymentType;
use App\Setting\Repository\PaymentTypeRepository;

class CreatePaymentTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly PaymentTypeRepository $paymentTypeRepository)
    {
    }

    public function __invoke(CreatePaymentTypeCommand $command): PaymentTypeResponse
    {
        $paymentType = new PaymentType();
        $paymentType->setLabel($command->getLabe());
        $this->paymentTypeRepository->persist($paymentType);
        $this->paymentTypeRepository->flush();
        return new PaymentTypeResponse($paymentType);
    }
}
