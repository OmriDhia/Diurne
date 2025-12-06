<?php

namespace App\Setting\Bus\Command\PaymentType;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Repository\PaymentTypeRepository;
use Doctrine\ORM\EntityNotFoundException;

class UpdatePaymentTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly PaymentTypeRepository $paymentTypeRepository)
    {
    }

    public function __invoke(UpdatePaymentTypeCommand $command): PaymentTypeResponse
    {
        $paymentType = $this->paymentTypeRepository->find($command->getId());

        if (!$paymentType) {
            throw new EntityNotFoundException('PaymentType not found');
        }

        if ($command->getLabel()) {
            $paymentType->setLabel($command->getLabel());
        }

        $this->paymentTypeRepository->persist($paymentType);
        $this->paymentTypeRepository->flush();

        return new PaymentTypeResponse($paymentType);
    }
}