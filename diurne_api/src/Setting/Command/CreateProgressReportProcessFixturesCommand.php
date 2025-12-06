<?php

declare(strict_types=1);

namespace App\Setting\Command;

use App\Setting\Entity\ProgressReportProcess;
use App\Setting\Repository\ProgressReportProcessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-progress-report-process-fixtures',
    description: 'Create progress report process fixtures'
)]
class CreateProgressReportProcessFixturesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProgressReportProcessRepository $processRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $processes = [
            'Préparation du graph',
            'Teinture des matériaux',
            'Lavage',
            'Clipping carving',
        ];

        foreach ($processes as $name) {
            $existing = $this->processRepository->findOneBy(['name' => $name]);
            if (!$existing instanceof ProgressReportProcess) {
                $process = new ProgressReportProcess();
                $process->setName($name);
                $this->entityManager->persist($process);
                $output->writeln(sprintf('Created progress report process: %s', $name));
            }
        }

        $this->entityManager->flush();
        $output->writeln('Progress report processes created successfully.');

        return Command::SUCCESS;
    }
}
