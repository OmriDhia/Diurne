<?php

declare(strict_types=1);

namespace App\Contact\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\Contact;
use App\Contact\Repository\ContactRepository;
use App\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMContactRepository extends DoctrineORMRepository implements ContactRepository
{
    protected const ENTITY_CLASS = Contact::class;
    protected const ALIAS = 'contact';

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

    public function findOneByCode($code)
    {
        try {
            $object = $this->query()
                ->where('contact.code = :code')
                ->setParameter('code', $code)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }

    public function findOneByUser(User $user)
    {
        try {
            $object = $this->query()
                ->join('contact.contactInformationSheet', 'cis') // Left join with User entity
                ->join('cis.user', 'u') // Left join with User entity
                ->where('u.id = :userId') // Filter by user ID
                ->setParameter('userId', $user->getId())
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }
}
