<?php

namespace App\Setting\Bus\Command\Currency;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Currency;
use App\Setting\Repository\CurrencyRepository;

class DeleteCurrencyCommandHandler implements CommandHandler
{
    public function __construct(private readonly CurrencyRepository $currencyRepository) {}

    public function __invoke(DeleteCurrencyCommand $command): CurrencyResponse
    {
        $currency = $this->currencyRepository->find($command->id);
        if (!$currency) {
            throw new RuntimeException('Currency not found', 404);
        }

        try {
            $this->currencyRepository->remove($currency);
            $this->currencyRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete currency: ' . $e->getMessage(), 0, $e);
        }

        return new CurrencyResponse($currency);
    }
}
