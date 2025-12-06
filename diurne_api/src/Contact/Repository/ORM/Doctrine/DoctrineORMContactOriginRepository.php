<?php

namespace App\Contact\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\ContactOrigin;
use App\Contact\Repository\ContactOriginRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMContactOriginRepository extends DoctrineORMRepository implements ContactOriginRepository
{
    protected const ENTITY_CLASS = ContactOrigin::class;
    protected const ALIAS = 'contactOrigin';

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
}