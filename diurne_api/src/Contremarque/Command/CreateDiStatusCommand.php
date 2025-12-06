<?php

namespace App\Contremarque\Command;

use App\Contremarque\Entity\DiStatus;
use App\Contremarque\Repository\DiStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-di-status',
    description: 'Creates default DiStatus entities'
)]
class CreateDiStatusCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DiStatusRepository $diStatusRepository
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
            $status = $this->diStatusRepository->findOneBy(['name' => $statusName]);
            if (!$status instanceof DiStatus) {
                $status = new DiStatus();
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
