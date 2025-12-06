<?php

namespace App\Setting\Command;

use RuntimeException;
use App\Setting\Entity\Country;
use App\Setting\Repository\CountryRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:load-countries',
    description: 'Load countries into the database from a JSON file'
)]
class LoadCountriesCommand extends Command
{
    public function __construct(
        private readonly CountryRepository $countryRepository,
        private readonly SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Read countries data from JSON file
        try {
            $countriesData = $this->readCountriesData();
        } catch (RuntimeException $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        foreach ($countriesData as $countryData) {
            $existingCountry = $this->countryRepository->findOneBy(['iso_code' => $countryData['iso_code']]);

            if (!$existingCountry) {
                $country = new Country();
                $country->setName($countryData['name']);
                $country->setIsoCode($countryData['iso_code']);
                $country->setZipCodeFormat($countryData['zip_code_format']);

                $this->countryRepository->persist($country);
                $output->writeln(sprintf('Added country: %s', $country->getName()));
            } else {
                $output->writeln(sprintf('Country already exists: %s', $existingCountry->getName()));
            }
        }

        $this->countryRepository->flush();
        return Command::SUCCESS;
    }

    private function readCountriesData(): array
    {
        $jsonFile = __DIR__ . '/../Resource/countries.json'; // Adjust path as necessary

        if (!file_exists($jsonFile)) {
            throw new RuntimeException('countries.json file not found.');
        }

        $jsonContent = file_get_contents($jsonFile);

        if (false === $jsonContent) {
            throw new RuntimeException('Failed to read countries.json file.');
        }

        // Deserialize JSON content into array
        $countriesData = json_decode($jsonContent, true);

        if (null === $countriesData) {
            throw new RuntimeException('Failed to decode JSON content.');
        }

        return $countriesData;
    }
}
