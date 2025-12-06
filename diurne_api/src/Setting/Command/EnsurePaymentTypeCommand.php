<?php

namespace App\Setting\Command;

use App\Setting\Entity\PaymentType;
use App\Setting\Repository\PaymentTypeRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:ensure-payment-types',
    description: 'Ensures that all default Payment Types values exist.'
)]
class EnsurePaymentTypeCommand extends Command
{
    private const ALLOWED_LABELS = [
        'Carte Bleu',
        'Chèque',
        'Virement',
        'Überweisung',
        'Banque Transfert',
        'Componsation',
    ];

    public function __construct(
        private readonly PaymentTypeRepository $paymentTypeRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->section('Seeding default Payment Types values...');
        foreach (self::ALLOWED_LABELS as $label) {
            $existing = $this->paymentTypeRepository->findOneBy(['label' => $label]);
            if (!$existing) {
                $paymentType = new PaymentType();
                $paymentType->setLabel($label);
                $this->paymentTypeRepository->save($paymentType, true);

                $io->success("Inserted Payment Type: $label");
            } else {
                $io->note("Already exists: $label");
            }
        }

        $io->success('Default Payment Types values seeded.');
        return Command::SUCCESS;
    }
}
