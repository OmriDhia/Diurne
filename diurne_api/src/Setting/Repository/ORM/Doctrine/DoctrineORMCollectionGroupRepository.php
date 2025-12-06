<?php

// src/App/Setting/Repository/ORM/Doctrine/DoctrineORMCollectionGroupRepository.php

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Repository\CollectionGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCollectionGroupRepository extends DoctrineORMRepository implements CollectionGroupRepository
{
    protected const ENTITY_CLASS = CollectionGroup::class;
    protected const ALIAS = 'collectionGroup';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(CollectionGroup $collectionGroup): void
    {
        $this->persist($collectionGroup);
        $this->flush();
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
}
