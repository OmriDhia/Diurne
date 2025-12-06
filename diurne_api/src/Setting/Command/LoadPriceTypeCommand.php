<?php

namespace App\Setting\Command;

use App\Setting\Entity\PriceType;
use App\Setting\Repository\PriceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:load-priceType',
    description: 'Load priceType into the database'
)]
class LoadPriceTypeCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PriceTypeRepository $priceTypeRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->loadPriceType();
            $io->success('Price types loaded successfully.');
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function loadPriceType(): void
    {
        $manager = $this->entityManager;
        $priceTypes = [
            ['name' => 'Tarif'],
            ['name' => 'Tarif grand projet'],
            ['name' => 'Remise proposee'],
            ['name' => 'Prix propose avant remise complementaire'],
            ['name' => 'Prix propose'],
        ];

        foreach ($priceTypes as $priceTypeData) {
            // Check if the PriceType already exists
            $existingPriceType = $this->priceTypeRepository->findOneBy(['name' => $priceTypeData['name']]);

            if (!$existingPriceType) {
                // Create a new PriceType entity
                $priceType = new PriceType();
                $priceType->setName($priceTypeData['name']);

                // Persist the new entity
                $manager->persist($priceType);
            }
        }

        // Flush the persisted entities to the database
        $manager->flush();
    }
}
