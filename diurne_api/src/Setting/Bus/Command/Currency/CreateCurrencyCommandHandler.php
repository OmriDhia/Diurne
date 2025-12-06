<?php

namespace App\Setting\Bus\Command\Currency;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Currency;
use App\Setting\Repository\CurrencyRepository;

class CreateCurrencyCommandHandler implements CommandHandler
{
    public function __construct(private readonly CurrencyRepository $currencyRepository)
    {
    }

    public function __invoke(CreateCurrencyCommand $command): CurrencyResponse
    {
        $currency = new Currency();
        $currency->setName($command->getName());

        $this->currencyRepository->save($currency, true);

        return new CurrencyResponse($currency);
    }
}
