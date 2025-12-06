<?php

declare(strict_types=1);

namespace App\Contact\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\AddressType;
use App\Contact\Repository\AddressTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMAddressTypeRepository extends DoctrineORMRepository implements AddressTypeRepository
{
    protected const ENTITY_CLASS = AddressType::class;
    protected const ALIAS = 'addressType';

    /**
     * DoctrineORMAddressTypeRepository constructor.
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
                ->where('addressType.name = :name')
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
     * @return AddressType
     */
    public function create(array $data)
    {
        $addressType = new AddressType();
        $addressType->setName($data['name']);
        $this->persist($addressType);
        $this->flush();

        return $addressType;
    }

    /**
     * @param object $addressType
     *
     * @return object
     */
    public function update($addressType, array $data)
    {
        $addressType->setName($data['name']);
        $this->persist($addressType);
        $this->flush();

        return $addressType;
    }

    /**
     * @return false|null|object
     */
    public function selectRandomAddressType(): object|false|null
    {
        $sql = 'SELECT at.id FROM address_type at ORDER BY RAND() ASC LIMIT 1';
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return false;
        }

        return $this->find((int) $id);
    }
}
