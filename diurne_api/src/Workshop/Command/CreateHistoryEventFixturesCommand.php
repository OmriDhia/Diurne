<?php

namespace App\Workshop\Command;

use App\Workshop\Entity\HistoryEventCategory;
use App\Workshop\Entity\HistoryEventType;
use App\Workshop\Entity\HistoryEventTypeCategory;
use App\Workshop\Repository\HistoryEventCategoryRepository;
use App\Workshop\Repository\HistoryEventTypeCategoryRepository;
use App\Workshop\Repository\HistoryEventTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-history-event-fixtures',
    description: 'Create history event categories, types and their relations'
)]
class CreateHistoryEventFixturesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly HistoryEventCategoryRepository $categoryRepository,
        private readonly HistoryEventTypeRepository $typeRepository,
        private readonly HistoryEventTypeCategoryRepository $typeCategoryRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Create or fetch categories
        $categories = [];
        foreach (["Event Prod", "Event Stock"] as $name) {
            $category = $this->categoryRepository->findOneByName(['name' => $name]);
            if (!$category instanceof HistoryEventCategory) {
                $category = new HistoryEventCategory();
                $category->setName($name);
                $this->entityManager->persist($category);
            }
            $categories[$name] = $category;
        }

        // Create or fetch event types
        $types = [];
        $typesData = [
            'Préparation de la comm.',
            'Tissage',
            'Finition de la Command',
            "Prêt à l'envoi (non check)",
            "Prêt à l'envoi (checké)",
            'Envoyé (checké)',
            'Envoyé (non checké)',
            'Consigné',
            'Présentation',
            'Vendu',
            'Expédié (Client)',
            'En Stock',
            'Annulé',
        ];

        foreach ($typesData as $name) {
            $type = $this->typeRepository->findOneByName(['name' => $name]);
            if (!$type instanceof HistoryEventType) {
                $type = new HistoryEventType();
                $type->setName($name);
                $this->entityManager->persist($type);
            }
            $types[$name] = $type;
        }

        // Define relations between categories and types
        $mapping = [
            'Event Stock' => [
                'Consigné',
                'Présentation',
                'Vendu',
                'Expédié (Client)',
                'En Stock',
                'Annulé',
            ],
            'Event Prod' => [
                'Préparation de la comm.',
                'Tissage',
                'Finition de la Command',
                "Prêt à l'envoi (non check)",
                "Prêt à l'envoi (checké)",
                'Envoyé (checké)',
                'Envoyé (non checké)',
                'Annulé',
            ],
        ];

        foreach ($mapping as $categoryName => $typeNames) {
            $category = $categories[$categoryName];
            foreach ($typeNames as $typeName) {
                $type = $types[$typeName];
                $existing = $this->typeCategoryRepository->findOneBy([
                    'eventTypeId' => $type,
                    'eventCategoryId' => $category,
                ]);
                if (!$existing instanceof HistoryEventTypeCategory) {
                    $relation = new HistoryEventTypeCategory();
                    $relation->setEventTypeId($type);
                    $relation->setEventCategoryId($category);
                    $this->entityManager->persist($relation);
                }
            }
        }

        $this->entityManager->flush();
        $output->writeln('History event fixtures created successfully.');

        return Command::SUCCESS;
    }
}

