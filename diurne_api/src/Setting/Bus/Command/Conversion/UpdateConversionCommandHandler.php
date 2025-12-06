<?php

namespace App\Setting\Bus\Command\Conversion;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Setting\Entity\Conversion;
use App\Setting\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateConversionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CurrencyRepository $currencyRepository
    ) {}

    public function __invoke(UpdateConversionCommand $command): UpdateConversionResponse
    {
        $conversion = $this->em->getRepository(Conversion::class)->find($command->conversionId);

        if (!$conversion) {
            throw new ValidationException(['Conversion not found']);
        }

        $dto = $command->dto;

        if ($dto->currencyId) {
            $currency = $this->currencyRepository->find((int) $dto->currencyId);
            $conversion->setCurrency($currency);
        }

        if ($dto->conversionDate) {
            $conversion->setConversionDate($dto->conversionDate);
        }

        if ($dto->coefficient) {
            $conversion->setCoefficient($dto->coefficient);
        }

        $this->em->persist($conversion);
        $this->em->flush();

        return new UpdateConversionResponse($conversion);
    }
}
