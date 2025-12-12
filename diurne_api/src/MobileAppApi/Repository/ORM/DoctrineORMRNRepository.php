<?php

declare(strict_types=1);

namespace App\MobileAppApi\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\MobileAppApi\Entity\RN;
use App\MobileAppApi\Repository\RNRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMRNRepository extends DoctrineORMRepository implements RNRepository
{
    protected const ENTITY_CLASS = RN::class;
    protected const ALIAS = 'rn';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function create(array $data): void
    {
        // TODO: Implement create() method.
    }

    public function update($entity, array $data): void
    {
        // TODO: Implement update() method.
    }

    public function save(RN $entity, bool $flush = false): void
    {
        $this->persist($entity);
        if ($flush) {
            $this->flush();
        }
    }
}
