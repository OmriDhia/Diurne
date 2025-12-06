<?php

declare(strict_types=1);

namespace App\Contact\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\CustomerGroup;
use App\Contact\Repository\CustomerGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMCustomerGroupRepository extends DoctrineORMRepository implements CustomerGroupRepository
{
    protected const ENTITY_CLASS = CustomerGroup::class;
    protected const ALIAS = 'contactGroup';

    /**
     * DoctrineORMCustomerGroupRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return |null
     */
    public function findByName($name)
    {
        try {
            $object = $this->query()
                ->where('contactGroup.name = :name')
                ->setParameter('name', $name)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }

    /**
     * @return CustomerGroup
     */
    public function create(array $data)
    {
        $contactGroup = new CustomerGroup();
        $contactGroup->setName($data['name']);
        $this->persist($contactGroup);
        $this->flush();

        return $contactGroup;
    }

    /**
     * @param object $contactGroup
     *
     * @return object
     */
    public function update($contactGroup, array $data)
    {
        $contactGroup->setName($data['name']);
        $this->persist($contactGroup);
        $this->flush();

        return $contactGroup;
    }

    /**
     * @return false|null|object
     */
    public function selectRandomCustomerGroup(): object|false|null
    {
        $sql = "SELECT c.id FROM customer_group c WHERE c.name!= 'Particulier (Client)' ORDER BY RAND() ASC LIMIT 1";
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return false;
        }

        return $this->find((int) $id);
    }
}
