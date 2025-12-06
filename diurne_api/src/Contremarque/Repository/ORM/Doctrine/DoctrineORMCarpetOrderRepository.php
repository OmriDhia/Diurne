<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Contremarque\Entity\CarpetOrder\CarpetOrder;
use App\Contremarque\Repository\CarpetOrderRepository;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCarpetOrderRepository extends DoctrineORMRepository implements CarpetOrderRepository
{
    protected const ENTITY_CLASS = CarpetOrder::class;
    protected const ALIAS = 'carpetOrder';


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

    public function getNextCarpetOrderNumber(): string
    {
        $date = new DateTime();
        $prefix = 'CM';
        $year = $date->format('y');
        $month = $date->format('m');

        $qb = $this->manager->createQueryBuilder()
            ->select('d')
            ->from(self::ENTITY_CLASS, 'd')
            ->where('d.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $date->format('Y-m-01 00:00:00')) // first day of the month
            ->setParameter('end', $date->format('Y-m-t 23:59:59'))  // last day of the month
            ->getQuery();

        $results = $qb->getResult();
        $count = count($results) + 1;

        return sprintf('%s%s%s%02d', $prefix, $month, $year, $count);
    }
}
