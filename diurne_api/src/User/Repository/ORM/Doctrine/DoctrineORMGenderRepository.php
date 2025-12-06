<?php

declare(strict_types=1);

namespace App\User\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\User\Entity\Gender;
use App\User\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMGenderRepository extends DoctrineORMRepository implements GenderRepository
{
    protected const ENTITY_CLASS = Gender::class;
    protected const ALIAS = 'gender';

    /**
     * DoctrineORMGenderRepository constructor.
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
                ->where('gender.name = :name')
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
     * @return Gender
     */
    public function create(array $data)
    {
        $gender = new Gender();
        $gender->setName($data['name']);
        $this->persist($gender);
        $this->flush();

        return $gender;
    }

    /**
     * @param object $gender
     *
     * @return object
     */
    public function update($gender, array $data)
    {
        $gender->setName($data['name']);
        $this->persist($gender);
        $this->flush();

        return $gender;
    }

    /**
     * @return false|null|object
     */
    public function selectRandomGender()
    {
        $sql = 'SELECT g.id FROM gender g ORDER BY RAND() ASC LIMIT 1';
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return false;
        }

        return $this->find((int) $id);
    }
}
