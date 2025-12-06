<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateHistoryEventCategory;

use App\Common\Bus\Command\CommandHandler;

use App\Common\Exception\DuplicateValidationResourceException;
use App\Workshop\Entity\HistoryEventCategory;
use App\Workshop\Repository\HistoryEventCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;


class CreateHistoryEventCategoryHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param HistoryEventCategoryRepository $categoryRepository
     */
    public function __construct(
        private readonly EntityManagerInterface         $entityManager,
        private readonly HistoryEventCategoryRepository $categoryRepository


    )
    {
    }

    /**
     * @param CreateHistoryEventCategoryCommand $command
     * @return HistoryEventCategoryResponse
     * @throws DuplicateValidationResourceException
     */
    public function __invoke(CreateHistoryEventCategoryCommand $command): HistoryEventCategoryResponse
    {
        $existingCategory = $this->categoryRepository->findOneByName(['name' => $command->name]);
        if ($existingCategory !== null) {
            throw new DuplicateValidationResourceException("A history event category with this name already exists");
        }
        $eventCategory = new HistoryEventCategory();
        $eventCategory->setName($command->name);

        $this->entityManager->persist($eventCategory);
        $this->entityManager->flush();

        return new HistoryEventCategoryResponse($eventCategory);
    }
}