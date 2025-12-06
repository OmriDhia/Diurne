<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\LayerDetail;
use App\Contremarque\Repository\LayerDetailRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMLayerDetailRepository extends DoctrineORMRepository implements LayerDetailRepository
{
    protected const ENTITY_CLASS = LayerDetail::class;
    protected const ALIAS = 'layerDetail';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * @return object[]
     *
     * @psalm-return list<object>
     */
    public function getRelatedThreadDetails($threadId): array
    {
        $sql = 'SELECT id FROM layer_detail WHERE thread_id = :threadId';

        // Prepare and execute the statement with a bound parameter
        $stmt = $this->manager->getConnection()->prepare($sql);
        $stmt->bindValue(':threadId', (int) $threadId);

        // Fetch the results
        $results = $stmt->execute()->fetchAllAssociative();

        // Check if any IDs are found
        if (empty($results)) {
            return [];
        }

        // Extract IDs into a flat array
        $ids = array_column($results, 'id');

        // Find the entities using the extracted IDs
        return $this->findBy(['id' => $ids]);
    }
}
