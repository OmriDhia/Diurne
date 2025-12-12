<?php

declare(strict_types=1);

namespace App\MobileAppApi\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\MobileAppApi\Entity\StockExit;
use App\MobileAppApi\Repository\StockExitRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMStockExitRepository extends DoctrineORMRepository implements StockExitRepository
{
    protected const ENTITY_CLASS = StockExit::class;
    protected const ALIAS = 'stockExit';

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

    public function save(StockExit $entity, bool $flush = false): void
    {
        $this->persist($entity);
        if ($flush) {
            $this->flush();
        }
    }
}
