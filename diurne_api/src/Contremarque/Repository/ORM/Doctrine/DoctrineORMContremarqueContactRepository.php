<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\Contact;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\ContremarqueContact;
use App\Contremarque\Repository\ContremarqueContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMContremarqueContactRepository extends DoctrineORMRepository implements ContremarqueContactRepository
{
    protected const ENTITY_CLASS = ContremarqueContact::class;
    protected const ALIAS = 'contremarqueContact';

    /**
     * DoctrineORMContremarqueContactRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function findOneByNumber($number)
    {
        try {
            $object = $this->query()
                ->where('contremarqueContact.project_number = :project_number')
                ->setParameter('project_number', $number)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
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

    public function exists(Contact $contact, Contremarque $contremarque): bool
    {
        try {
            // Get the total count of locations for the given Contremarque
            $qb = $this->manager->createQueryBuilder('cc')
                ->from(self::ENTITY_CLASS, 'cc')
                ->select('COUNT(cc.id)')
                ->where('cc.contact = :contact')
                ->andWhere('cc.contremarque = :contremarque')
                ->setParameter('contact', $contact)
                ->setParameter('contremarque', $contremarque);
            $count = (int) $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }

        return $count > 0;
    }
}
