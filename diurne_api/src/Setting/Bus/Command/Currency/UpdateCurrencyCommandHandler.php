<?php

namespace App\Setting\Bus\Command\Currency;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\CurrencyRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateCurrencyCommandHandler implements CommandHandler
{
    public function __construct(private readonly CurrencyRepository $currencyRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateCurrencyCommand $command): CurrencyResponse
    {
        $currency = $this->currencyRepository->find((int) $command->getId());

        if (null === $currency) {
            throw new ResourceNotFoundException();
        }
        $currency->setName($command->getName());
        // Add your entity fields here

        $this->currencyRepository->save($currency, true);

        return new CurrencyResponse($currency);
    }
}
