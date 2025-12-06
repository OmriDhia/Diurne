<?php

namespace App\Setting\Command;

use App\Setting\Entity\Police;
use App\Setting\Entity\SpecialShape;
use App\Setting\Repository\PoliceRepository;
use App\Setting\Repository\SpecialShapeRepository;
use App\Setting\Repository\TarifGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-collections',
    description: 'Creates collections from predefined JSON files.'
)]
class CreateCollectionCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $files = [
            'police.json' => Police::class,
            'special_form.json' => SpecialShape::class,
        ];

        foreach ($files as $filename => $entityClass) {
            $filePath = __DIR__ . '/../Resource/' . $filename;
            if (!$this->processJsonFile($filePath, $io, $entityClass)) {
                return Command::FAILURE;
            }
        }

        $io->success('Collections created successfully.');
        return Command::SUCCESS;
    }

    private function processJsonFile(string $filePath, SymfonyStyle $io, string $entityClass): bool
    {
        if (!file_exists($filePath)) {
            $io->error('JSON file not found at path: ' . $filePath);
            return false;
        }

        $jsonData = file_get_contents($filePath);
        $dataArray = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $io->error('Error decoding JSON: ' . json_last_error_msg());
            return false;
        }

        $repository = $this->entityManager->getRepository($entityClass);

        foreach ($dataArray as $label) {
            if ($repository->findOneBy(['label' => $label])) {
                $io->text($entityClass . ' already exists: ' . $label);
                continue;
            }

            $entity = new $entityClass();
            $entity->setLabel($label);
            $this->entityManager->persist($entity);
            $io->text('Inserted ' . $entityClass . ': ' . $label);
        }

        $this->entityManager->flush();
        return true;
    }
}
