<?php

namespace App\Contremarque\Command;

use App\Contact\Repository\AttributionStatusRepository;
use App\Contact\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:refactor-commercial',
    description: 'Refactor commercial histories'
)]
class RefactorCommercialCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CustomerRepository $customerRespository,
        private readonly AttributionStatusRepository $attributionStatusRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $customers = $this->customerRespository->findAll();
        if (count($customers)) {
            foreach ($customers as $customer) {
                $commercials = $customer->getContactCommercialHistories();
                if (count($commercials)) {
                    foreach ($commercials as $index => $commercialHistory) {
                        // Refactor logic for commercial histories
                        if ($index == count($commercials) - 1) {
                            $status = ['Accepted', 'Pending'];
                            $randomKey = array_rand($status);
                            $status[$randomKey]; // The result of this is not used; remove if not needed.
                            $st = $this->attributionStatusRepository->findOneBy(['name' => 'Accepted']);
                            $commercialHistory->setToDate(null);
                            $commercialHistory->setStatus($st);
                            $this->entityManager->persist($commercialHistory);
                        }
                        if (($index == count($commercials) - 2) || 0 == $index) {
                            $st = $this->attributionStatusRepository->findOneBy(['name' => 'Accepted']);
                            $commercialHistory->setStatus($st);
                            $this->entityManager->persist($commercialHistory);
                        }
                    }
                }
            }
        }

        $this->entityManager->flush();

        $io->success('Commercials are all refactored');

        return Command::SUCCESS;
    }
}
