<?php

declare(strict_types=1);

namespace App\Contact\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\AttributionStatus;
use App\Contact\Repository\AttributionStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMAttributionStatusRepository extends DoctrineORMRepository implements AttributionStatusRepository
{
    protected const ENTITY_CLASS = AttributionStatus::class;
    protected const ALIAS = 'attributionStatus';

    /**
     * DoctrineORMContactRepository constructor.
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

    public function findOneByName($name)
    {
        try {
            $object = $this->query()
                ->where('attributionStatus.name = :name')
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
