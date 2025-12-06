<?php

namespace App\Contremarque\Command;

use DateTime;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-image-commands',
    description: 'Creates 150 ImageCommand entities with sample data'
)]
class CreateImageCommandsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Creating 150 ImageCommand entities...');

        $currentDateTime = new DateTime();

        for ($i = 1; $i <= 150; $i++) {
            $imageCommand = new ImageCommand();

            $commandNumber = sprintf('CMD-%s-%03d', date('Ymd'), $i);
            $imageCommand->setCommandNumber($commandNumber);

            $imageCommand->setCreatedAt(clone $currentDateTime);
            $imageCommand->setUpdatedAt(clone $currentDateTime);

            $imageCommand->setCommercialComment("Commercial comment for command #$i");
            $imageCommand->setAdvComment("ADV comment for command #$i");
            $imageCommand->setRn("RN" . str_pad((string)$i, 3, '0', STR_PAD_LEFT));
            $imageCommand->setStudioComment("Studio comment for command #$i");


            $this->entityManager->persist($imageCommand);

            if ($i % 50 === 0) {
                $this->entityManager->flush();
                $io->writeln("Processed $i ImageCommands...");
            }
        }

        $this->entityManager->flush();

        $io->success('Successfully created 150 ImageCommand entities!');
        $io->writeln('Command numbers generated in format: ' . $commandNumber . ' (example)');

        return Command::SUCCESS;
    }
}
