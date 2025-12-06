<?php

declare(strict_types=1);

namespace App\Contact\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\IntermediaryType;
use App\Contact\Repository\IntermediaryTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMIntermediaryTypeRepository extends DoctrineORMRepository implements IntermediaryTypeRepository
{
    protected const ENTITY_CLASS = IntermediaryType::class;
    protected const ALIAS = 'intermediaryType';

    /**
     * DoctrineORMIntermediaryTypeRepository constructor.
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

    /**
     * @return false|null|object
     */
    public function selectRandomIntermediaryType(): object|false|null
    {
        $sql = 'SELECT i.id FROM intermediary_type i ORDER BY RAND() ASC LIMIT 1';
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return false;
        }

        return $this->find((int) $id);
    }

    public function findOneByName($name)
    {
        try {
            $object = $this->query()
                ->where('intermediaryType.name = :name')
                ->setParameter('name', $name)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }
}
