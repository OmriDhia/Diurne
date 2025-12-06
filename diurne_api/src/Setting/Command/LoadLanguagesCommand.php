<?php

namespace App\Setting\Command;

use App\Setting\Entity\Language;
use App\Setting\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:load-languages',
    description: 'Load languages into the database'
)]
class LoadLanguagesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LanguageRepository $languageRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->loadLanguages();
            $io->success('Languages loaded successfully.');
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function loadLanguages(): void
    {
        $manager = $this->entityManager;

        $languages = [
            ['name' => 'Français', 'iso_code' => 'fr'],
            ['name' => 'Anglais', 'iso_code' => 'en'],
            ['name' => 'Allemand', 'iso_code' => 'de'],
        ];

        foreach ($languages as $languageData) {
            // Check if the language already exists
            $existingLanguage = $this->languageRepository->findOneBy(['iso_code' => $languageData['iso_code']]);

            if (!$existingLanguage) {
                $language = new Language();
                $language->setName($languageData['name']);
                $language->setIsoCode($languageData['iso_code']);
                $manager->persist($language);
            }
        }

        $manager->flush();
    }
}
