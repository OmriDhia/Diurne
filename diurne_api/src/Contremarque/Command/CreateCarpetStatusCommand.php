<?php

// src/Contremarque/Command/CreateCarpetStatusCommand.php

namespace App\Contremarque\Command;

use App\Contremarque\Entity\CarpetStatus;
use App\Contremarque\Repository\CarpetStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:create-carpet-status',
    description: 'Creates default CarpetStatus entities'
)]
class CreateCarpetStatusCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CarpetStatusRepository $carpetStatusRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $statuses = [
            'Non transmis',
            'Transmis',
            'Attribué',
            'En cours',
            'En pause',
            'Fini',
            'Annul Studio',
            'Annul Commercial',
        ];

        foreach ($statuses as $statusName) {
            $status = $this->carpetStatusRepository->findOneBy(['name' => $statusName]);
            if (!$status instanceof CarpetStatus) {
                $status = new CarpetStatus();
                $status->setName($statusName);
                $this->entityManager->persist($status);
                $output->writeln(sprintf('Created status: %s', $statusName));
            }
        }

        $this->entityManager->flush();

        $output->writeln('All statuses created successfully.');

        return Command::SUCCESS;
    }
}
