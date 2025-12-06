<?php

declare(strict_types=1);

namespace App\User\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\User\Entity\Profile;
use App\User\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMProfileRepository extends DoctrineORMRepository implements ProfileRepository
{
    protected const ENTITY_CLASS = Profile::class;
    protected const ALIAS = 'profile';

    /**
     * DoctrineORMProfileRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return |null
     */
    public function findOneByName($name)
    {
        try {
            $object = $this->query()
                ->where('profile.name = :name')
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
     * @return Profile
     */
    public function create(array $data)
    {
        $profile = new Profile();
        $profile->setName($data['name']);
        $profile->setDiscount($data['discount']);
        $this->persist($profile);
        $this->flush();

        return $profile;
    }

    /**
     * @param object $profile
     *
     * @return object
     */
    public function update($profile, array $data)
    {
        $profile->setDiscount($data['discount']);
        $this->persist($profile);
        $this->flush();

        return $profile;
    }
}
