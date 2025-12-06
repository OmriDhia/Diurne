<?php

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\ImageCommand\ImageCommandDesignerAssignment;
use App\Contremarque\Repository\ImageCommandDesignerAssignmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMImageCommandDesignerAssignmentRepository extends DoctrineORMRepository implements ImageCommandDesignerAssignmentRepository
{
    protected const ENTITY_CLASS = ImageCommandDesignerAssignment::class;
    protected const ALIAS = 'imageCommandDesignerAssignment';

    public function __construct(
        EntityManagerInterface $manager,
    )
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
}