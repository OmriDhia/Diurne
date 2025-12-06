<?php

namespace App\Setting\Bus\Command\CollectionGroup;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\CollesetGroupNumberctionGroup;
use App\Setting\Repository\CollectionGroupRepository;

class UpdateCollectionGroupCommandHandler implements CommandHandler
{
    public function __construct(private readonly CollectionGroupRepository $collectiongroupRepository) {}

    public function __invoke(UpdateCollectionGroupCommand $command): CollectionGroupResponse
    {
        $collectiongroup = $this->collectiongroupRepository->find($command->id);
        if (!$collectiongroup) {
            throw new RuntimeException('CollectionGroup not found', 404);
        }
        
        if ($command->collection_group_id) $collectiongroup->setGroupNumber($command->collection_group_id);

        try {
            $this->collectiongroupRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to update collectiongroup: ' . $e->getMessage(), 0, $e);
        }

        return new CollectionGroupResponse($collectiongroup);
    }
}
