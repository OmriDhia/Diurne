<?php

namespace App\Setting\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:run-setting-fixtures',
    description: 'Runs all necessary fixture commands.'
)]
class ExecuteSettingFixturesCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commands = [
            'app:load-languages',
            'app:load-countries',
            'app:load-discounts',
            'app:import-quality',
            'app:create-collections',
            'app:import-carriers',
            'app:load-collection-groups',
            'app:import-collections',
            'app:import-models',
            'app:import-manufacturers',
            'app:import-materials',
            'app:import-colors',
            'app:import-dominant-color',
            'app:conversion-create',
            'app:load-priceType',
            'app:load-payment-types',
            'app:import-quality-tarif-texture',
            'app:import-tarif',
            'app:import-material-price',
            'app:ensure-contact-origins',
            'app:ensure-payment-types',
            'app:ensure-image-types',
        ];

        foreach ($commands as $command) {
            $process = new Process(['php', 'bin/console', $command]);
            $process->setWorkingDirectory('/var/www/html/api_diurne'); // Ensure this is the correct path

            try {
                $process->mustRun();
                $output->writeln($process->getOutput());
            } catch (ProcessFailedException $e) {
                $output->writeln("<error>Command '$command' failed: " . $e->getMessage() . "</error>");

                return Command::FAILURE;
            }
        }

        $output->success('All fixture commands have been executed successfully.');

        return Command::SUCCESS;
    }
}
