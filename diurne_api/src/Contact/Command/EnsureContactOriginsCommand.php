<?php

declare(strict_types=1);

namespace App\Contact\Command;

use App\Contact\Entity\ContactOrigin;
use App\Contact\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:ensure-contact-origins',
    description: 'Ensures that all default ContactOrigin values exist and assigns a random origin to customers missing one.'
)]
class EnsureContactOriginsCommand extends Command
{
    private const ALLOWED_LABELS = [
        'Passage galerie',
        'Contact mail entrant',
        'Contact Tel entrant',
        'Contact Site entrant',
        'Prospection Presse',
        'Prospection réseau sociaux',
        'Client de prescripteur',
        'Autre'
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Seeding ContactOrigin values
        $io->section('Seeding default ContactOrigin values...');
        $contactOriginRepository = $this->entityManager->getRepository(ContactOrigin::class);

        foreach (self::ALLOWED_LABELS as $label) {
            if (!$contactOriginRepository->findOneBy(['label' => $label])) {
                $origin = new ContactOrigin();
                $origin->setLabel($label);
                $this->entityManager->persist($origin);
                $io->writeln("Inserted ContactOrigin: $label");
            } else {
                $io->writeln("Already exists: $label");
            }
        }

        $this->entityManager->flush();
        $io->success('Default ContactOrigin values seeded.');

        // Assigning random ContactOrigin to customers missing one
        $io->section('Assigning random ContactOrigin to customers missing one...');
        $allOrigins = $contactOriginRepository->findAll();

        if (empty($allOrigins)) {
            $io->warning('No ContactOrigin values found; cannot assign random origin.');
            return Command::FAILURE;
        }

        $customerRepository = $this->entityManager->getRepository(Customer::class);
        $customersWithoutOrigin = $customerRepository->findBy(['contactOrigin' => null]);

        foreach ($customersWithoutOrigin as $customer) {
            $randomOrigin = $allOrigins[array_rand($allOrigins)];
            $customer->setContactOrigin($randomOrigin);
            $io->writeln("Assigned '{$randomOrigin->getLabel()}' to customer ID: " . $customer->getId());
        }

        $this->entityManager->flush();
        $io->success('Random ContactOrigin assigned to all customers missing one.');

        return Command::SUCCESS;
    }
}
