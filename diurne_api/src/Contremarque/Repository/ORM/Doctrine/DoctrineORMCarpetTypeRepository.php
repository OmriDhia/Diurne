<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetType;
use App\Contremarque\Repository\CarpetTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMCarpetTypeRepository extends DoctrineORMRepository implements CarpetTypeRepository
{
    protected const ENTITY_CLASS = CarpetType::class;
    protected const ALIAS = 'carpetType';

    /**
     * DoctrineORMCarpetTypeRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return CarpetType
     */
    public function create(array $data)
    {
        $carpetType = new CarpetType();
        $carpetType->setName($data['name']);
        $this->persist($carpetType);
        $this->flush();

        return $carpetType;
    }

    /**
     * @param object $carpetType
     *
     * @return object
     */
    public function update($carpetType, array $data)
    {
        $carpetType->setName($data['name']);
        $this->persist($carpetType);
        $this->flush();

        return $carpetType;
    }

    public function findOneByName($name)
    {
        try {
            $object = $this->query()
                ->where('carpetType.name = :name')
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
