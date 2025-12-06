<?php

namespace App\Setting\Command;

use DateTimeImmutable;
use App\Setting\Entity\Conversion;
use App\Setting\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:conversion-create', description: 'Create conversion rate')]
class CreateConversionCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CurrencyRepository $currencyRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $currency = $this->currencyRepository->findOneBy(['name' => 'Dollars']);

        if (!$currency) {
            $io->error('Currency "Dollars" not found. Please check your database.');
            return Command::FAILURE;
        }

        $cours = [
            ['date' => '01-01-2016', 'value' => '1.09051'],
            ['date' => '27-01-2016', 'value' => '1.308709'],
            ['date' => '27-08-2019', 'value' => '1.11'],
            ['date' => '07-10-2021', 'value' => '1.3572'],
        ];

        foreach ($cours as $data) {
            try {
                $conversionDate = new DateTimeImmutable($data['date']);
                $coefficient = (float) $data['value'];
            } catch (\Exception $e) {
                $io->error("Invalid date or value format for entry: " . json_encode($data));
                continue;
            }

            $conversion = new Conversion();
            $conversion->setCurrency($currency);
            $conversion->setConversionDate($conversionDate);
            $conversion->setCoefficient($coefficient);

            $this->entityManager->persist($conversion);
        }

        $this->entityManager->flush();
        $io->success('Conversions created successfully.');

        return Command::SUCCESS;
    }
}
