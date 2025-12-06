<?php

namespace App\Contremarque\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:run-contremarque-commands',
    description: 'Runs a series of Contremarque related commands.'
)]
class ExecuteContremarqueCommands extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commands = [
            'app:create-carpet-status',
            'app:create-di-status',
            'app:create-measurement',
            'app:create-units-of-measurement',
            'app:create-contremarque-data-fixtures',
            'app:refactor-commercial',
        ];

        foreach ($commands as $command) {
            $output->writeln("<info>Running command: $command</info>");
            $process = new Process(['php', 'bin/console', $command]);
            $process->setWorkingDirectory('/var/www/html/api_diurne'); // Ensure this is the correct path
            $process->setTimeout(600); // Optional: Set timeout (10 minutes)

            try {
                $process->mustRun();
                $output->writeln($process->getOutput());
            } catch (ProcessFailedException $e) {
                $output->writeln('<error>Command failed: ' . $e->getMessage() . '</error>');
                $output->writeln('<error>Output: ' . $process->getErrorOutput() . '</error>');

                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }
}
