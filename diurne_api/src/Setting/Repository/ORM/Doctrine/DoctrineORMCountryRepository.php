<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Country;
use App\Setting\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMCountryRepository extends DoctrineORMRepository implements CountryRepository
{
    protected const ENTITY_CLASS = Country::class;
    protected const ALIAS = 'country';

    /**
     * DoctrineORMCountryRepository constructor.
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
                ->where('country.name = :name')
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
     * @return Country
     */
    public function create(array $data)
    {
        $country = new Country();
        $country->setName($data['name']);
        $country->setIsoCode($data['iso_code']);
        $this->persist($country);
        $this->flush();

        return $country;
    }

    /**
     * @param object $country
     *
     * @return object
     */
    public function update($country, array $data)
    {
        $country->setName($data['name']);
        $country->setIsoCode($data['iso_code']);
        $this->persist($country);
        $this->flush();

        return $country;
    }
}
