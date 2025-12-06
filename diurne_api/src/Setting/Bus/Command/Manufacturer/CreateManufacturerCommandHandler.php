<?php

namespace App\Setting\Bus\Command\Manufacturer;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Manufacturer;
use App\Setting\Repository\CountryRepository;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\ManufacturerRepository;

class CreateManufacturerCommandHandler implements CommandHandler
{
    private readonly CurrencyRepository $currencyRepository;

    public function __construct(private readonly ManufacturerRepository $manufacturerRepository, private readonly CountryRepository $countryRepository, CurrencyRepository $currencyRepository)
    {
    }

    public function __invoke(CreateManufacturerCommand $command): ManufacturerResponse
    {
        $country = $this->countryRepository->find((int) $command->getCarpetCountry());
        $currency = $this->countryRepository->find((int) $command->getCurrency());

        $manufacturer = new Manufacturer();
        $manufacturer->setName($command->getName());
        $manufacturer->setCompany($command->getCompany());
        $manufacturer->setCarpetPrefix($command->getCarpetPrefix());
        $manufacturer->setSamplePrefix($command->getSamplePrefix());
        $manufacturer->setCreditAmount($command->getCreditAmount());
        $manufacturer->setComplexityBonus($command->getComplexityBonus());
        $manufacturer->setOversizeBonus($command->getOversizeBonus());
        $manufacturer->setOversizeMohaiBonus($command->getOversizeMohaiBonus());
        $manufacturer->setMultiLevelBonus($command->getMultiLevelBonus());
        $manufacturer->setSpecialFormBonus($command->getSpecialFormBonus());
        $manufacturer->setCarpetCountry($country);
        $manufacturer->setCurrency($currency);

        $this->manufacturerRepository->save($manufacturer, true);

        return new ManufacturerResponse($manufacturer);
    }
}
