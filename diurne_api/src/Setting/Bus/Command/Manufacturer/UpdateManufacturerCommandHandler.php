<?php

namespace App\Setting\Bus\Command\Manufacturer;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\CountryRepository;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\ManufacturerRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateManufacturerCommandHandler implements CommandHandler
{
    public function __construct(private readonly ManufacturerRepository $manufacturerRepository, private readonly CountryRepository $countryRepository, private readonly CurrencyRepository $currencyRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateManufacturerCommand $command): ManufacturerResponse
    {
        $manufacturer = $this->manufacturerRepository->find((int) $command->getId());

        if (null === $manufacturer) {
            throw new ResourceNotFoundException();
        }
        $country = $this->countryRepository->find((int) $command->getCountryId());
        $currency = $this->currencyRepository->find((int) $command->getCurrencyId());
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
