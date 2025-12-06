<?php

namespace App\Common\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:run-fixtures',
    description: 'Runs all necessary fixture commands.'
)]
class ExecuteFixturesCommand extends Command
{
    protected function configure(): void
    {
        $this->setHelp('This command allows you to run a series of fixture commands.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        $commands = [
            'doctrine:monolithic:fixtures:load',
            'app:run-setting-fixtures',
            'app:run-customer-fixtures',
            'app:run-contremarque-commands',
        ];

        $workingDirectory = '/var/www/html/api_diurne'; // Ensure this is correct

        foreach ($commands as $command) {
            $output->writeln("<info>Running: $command</info>");

            $process = new Process(['php', 'bin/console', $command]);
            $process->setWorkingDirectory($workingDirectory);
            $process->setTimeout(null); // Prevents timeout issues

            try {
                $process->mustRun();
                $output->writeln($process->getOutput());
            } catch (ProcessFailedException $e) {
                $output->writeln("<error>Command '$command' failed: {$e->getMessage()}</error>");
                return Command::FAILURE;
            }
        }

        $output->writeln('<info>All fixture commands executed successfully.</info>');
        return Command::SUCCESS;
    }
}
