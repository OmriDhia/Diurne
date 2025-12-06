<?php

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Contremarque\Repository\ImageCommandRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMImageCommandRepository extends DoctrineORMRepository implements ImageCommandRepository
{
    protected const ENTITY_CLASS = ImageCommand::class;
    protected const ALIAS = 'imageCommand';

    public function __construct(
        EntityManagerInterface $manager,
    ) {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function findOneByCarpetDesignOrder($id): ?ImageCommand
    {
        return $this->manager->createQueryBuilder()
            ->select('imageCommand')
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->where('imageCommand.objectId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * @return array<ImageCommand>
     */
    public function findAllImageCommands(): array
    {
        return $this->manager->createQueryBuilder()
            ->select('ic')
            ->from(self::ENTITY_CLASS, 'ic')
            ->leftJoin('ic.imageCommandDesignerAssignments', 'a')
            ->leftJoin('a.designer', 'd')
            ->leftJoin('ic.carpetDesignOrder', 'cdo')
            ->addSelect('a', 'd', 'cdo')
            ->getQuery()
            ->getResult();
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

    public function findByDesigner(int $designerId)
    {
        return $this->manager->createQueryBuilder()
            ->select('imageCommand')
            ->from(ImageCommand::class, 'imageCommand')
            ->innerJoin('imageCommand.imageCommandDesignerAssignments', 'assignments')
            ->where('assignments.designer = :designerId')
            ->setParameter('designerId', $designerId)
            ->getQuery()
            ->getResult();
    }
}
