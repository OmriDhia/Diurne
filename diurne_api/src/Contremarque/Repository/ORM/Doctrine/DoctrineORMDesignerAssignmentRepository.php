<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\DesignerAssignment;
use App\Contremarque\Repository\DesignerAssignmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMDesignerAssignmentRepository extends DoctrineORMRepository implements DesignerAssignmentRepository
{
    protected const ENTITY_CLASS = DesignerAssignment::class;
    protected const ALIAS = 'designerAssignment';

    /**
     * DoctrineORMDesignerAssignmentRepository constructor.
     */
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
}
