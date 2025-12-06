<?php

namespace App\Setting\Bus\Command\CollectionGroup;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Repository\CollectionGroupRepository;

class DeleteCollectionGroupCommandHandler implements CommandHandler
{
    public function __construct(private readonly CollectionGroupRepository $collectiongroupRepository) {}

    public function __invoke(DeleteCollectionGroupCommand $command): CollectionGroupResponse
    {
        $collectiongroup = $this->collectiongroupRepository->find($command->id);
        if (!$collectiongroup) {
            throw new RuntimeException('CollectionGroup not found', 404);
        }

        try {
            $this->collectiongroupRepository->remove($collectiongroup);
            $this->collectiongroupRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete collectiongroup: ' . $e->getMessage(), 0, $e);
        }

        return new CollectionGroupResponse($collectiongroup);
    }
}
