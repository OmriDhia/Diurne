<?php

namespace App\ProgressReport\Command;

use App\ProgressReport\Entity\ProgressReportStatus;
use App\ProgressReport\Repository\ProgressReportStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-progress-report-status-fixtures',
    description: 'Create progress report status fixtures'
)]
class CreateProgressReportStatusFixturesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface         $entityManager,
        private readonly ProgressReportStatusRepository $statusRepository
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $statuses = [
            'Préparation de la Commande',
            'Tissage',
            'Finition de la Commande',
            'Prêt à l’envoi',
            'Envoyé'
        ];

        foreach ($statuses as $label) {
            $existing = $this->statusRepository->findOneBy(['status' => $label]);
            if (!$existing instanceof ProgressReportStatus) {
                $status = new ProgressReportStatus();
                $status->setStatus($label);
                $this->entityManager->persist($status);
                $output->writeln(sprintf('Created progress report status: %s', $label));
            }
        }

        $this->entityManager->flush();
        $output->writeln('Progress report statuses created successfully.');

        return Command::SUCCESS;
    }
}
