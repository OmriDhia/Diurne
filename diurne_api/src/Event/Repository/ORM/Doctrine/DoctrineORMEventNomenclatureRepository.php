<?php

declare(strict_types=1);

namespace App\Event\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Event\Entity\EventNomenclature;
use App\Event\Repository\EventNomenclatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMEventNomenclatureRepository extends DoctrineORMRepository implements EventNomenclatureRepository
{
    protected const ENTITY_CLASS = EventNomenclature::class;
    protected const ALIAS = 'eventNomenclature';

    /**
     * DoctrineORMEventNomenclatureRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return EventNomenclature
     */
    public function create(array $data)
    {
        $eventNomenclature = new EventNomenclature();
        $eventNomenclature->setSubject($data['subject']);
        $eventNomenclature->setAutomaticFollowupDelay($data['automatic_followup_delay']);
        $eventNomenclature->setIsAutomatic($data['is_automatic']);
        $this->persist($eventNomenclature);
        $this->flush();

        return $eventNomenclature;
    }

    /**
     * @return object
     */
    public function update($eventNomenclature, array $data)
    {
        if (!empty($data['subject'])) {
            $eventNomenclature->setSubject($data['subject']);
        }
        if (!empty($data['automatic_followup_delay'])) {
            $eventNomenclature->setAutomaticFollowupDelay($data['automatic_followup_delay']);
        }
        if (null !== $data['is_automatic']) {
            $eventNomenclature->setIsAutomatic($data['is_automatic']);
        }

        $this->persist($eventNomenclature);
        $this->flush();

        return $eventNomenclature;
    }

    /**
     * @return |null
     */
    public function findBySubject($subject)
    {
        try {
            $object = $this->query()
                ->where('eventNomenclature.subject = :name')
                ->setParameter('name', $subject)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }
}
