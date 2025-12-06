<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetCountries;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\CountryRepository;

final readonly class GetCountriesQueryHandler implements QueryHandler
{
    public function __construct(private CountryRepository $countryRepository)
    {
    }

    public function __invoke(GetCountriesQuery $query)
    {
        $countries = $this->countryRepository->findAll();

        $formattedCountries = array_map(fn($country) => [
            'country_id' => $country->getId(),
            'name' => $country->getName(),
            'is_code' => $country->getIsoCode(),
        ], $countries);

        return new GetCountriesResponse(
            $formattedCountries
        );
    }
}
