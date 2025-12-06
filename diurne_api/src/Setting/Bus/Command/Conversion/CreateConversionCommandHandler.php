<?php

namespace App\Setting\Bus\Command\Conversion;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Conversion;
use App\Setting\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateConversionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CurrencyRepository $currencyRepository
    ) {
    }

    public function __invoke(CreateConversionCommand $command)
    {
        $dto = $command->dto;
        $currency = $this->currencyRepository->find((int) $dto->currencyId);

        $conversion = new Conversion();
        $conversion->setCurrency($currency);
        $conversion->setConversionDate($dto->conversionDate);
        $conversion->setCoefficient($dto->coefficient);

        $this->em->persist($conversion);
        $this->em->flush();

        return new CreateConversionResponse($conversion);
    }
}
