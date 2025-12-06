<?php

namespace App\Setting\Command;

use App\Setting\Entity\PaymentType;
use App\Setting\Repository\PaymentTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-payment-types',
    description: 'Load default payment types into the database'
)]
class LoadPaymentTypesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PaymentTypeRepository $paymentTypeRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->loadPaymentTypes();
            $io->success('Payment types loaded successfully.');
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function loadPaymentTypes(): void
    {
        $manager = $this->entityManager;

        $types = [
            ['label' => 'Carte Bleue'],
            ['label' => 'Chèque'],
            ['label' => 'Virement'],
            ['label' => 'Überweisung'],
            ['label' => 'Bank Transfer'],
            ['label' => 'Compensation'],
        ];

        foreach ($types as $typeData) {
            $existing = $this->paymentTypeRepository->findOneBy(['label' => $typeData['label']]);

            if (!$existing) {
                $paymentType = new PaymentType();
                $paymentType->setLabel($typeData['label']);
                $manager->persist($paymentType);
            }
        }

        $manager->flush();
    }
}
