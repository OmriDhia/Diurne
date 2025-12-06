<?php

namespace App\Setting\Command;

use App\Setting\Entity\ImageCategoryEnum;
use App\Setting\Entity\ImageType;
use App\Setting\Repository\ImageTypeRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:ensure-image-types',
    description: 'Ensures that all default Image Types values exist.'
)]
class EnsureImageTypeCommand extends Command
{
    private const IMAGE_TYPES = [
        ['name' => 'Vignette', 'category' => 'Studio'],
        ['name' => 'Légende A4', 'category' => 'Studio'],
        ['name' => 'Légende A3', 'category' => 'Studio'],
        ['name' => 'A3', 'category' => 'Atelier'],
        ['name' => 'A4', 'category' => 'Atelier'],
        ['name' => 'Insertion Plan', 'category' => 'Atelier'],
        ['name' => 'Projet d\'atelier', 'category' => 'Atelier'],
        ['name' => 'Détail A4', 'category' => 'Atelier'],
        ['name' => 'Détail A3', 'category' => 'Atelier'],
    ];

    public function __construct(
        private readonly ImageTypeRepository $imageTypeRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->section('Seeding default Image Types values...');
        foreach (self::IMAGE_TYPES as $type) {
            $existing = $this->imageTypeRepository->findOneBy(['name' => $type['name']]);
            if (!$existing) {
                $imageType = new ImageType();
                $imageType->setName($type['name']);
                $imageType->setDescription($type['name']);
                $imageType->setCategory(ImageCategoryEnum::from($type['category']));
                $this->imageTypeRepository->save($imageType, true);

                $io->success("Inserted Image Type: {$type['name']}");
            } else {
                $io->note("Already exists: {$type['name']}");
            }
        }

        $io->success('Default Image Types values seeded.');
        return Command::SUCCESS;
    }
}
