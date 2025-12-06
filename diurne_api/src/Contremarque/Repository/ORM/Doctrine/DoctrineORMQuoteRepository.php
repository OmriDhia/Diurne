<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use DateTime;
use App\Contremarque\Entity\QuoteDetail;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\Quote;
use App\Contremarque\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMQuoteRepository extends DoctrineORMRepository implements QuoteRepository
{
    protected const ENTITY_CLASS = Quote::class;
    protected const ALIAS = 'quote';

    /**
     * DoctrineORMQuoteRepository constructor.
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

    public function getNextQuoteNumber(): string
    {
        $date = new DateTime();
        $prefix = 'D';
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

    public function findOneById(int $id): ?Quote
    {
        return $this->find((int) $id);
    }
    /**
     * Removes all data from the carpet_specific_treatment, carpet_price_simulator,
     * carpet_price_base, quote_detail, and quote tables in the database.
     *
     * This function connects to the database and executes SQL DELETE queries
     * to clear the data from the specified tables.
     */

    public function cleanOldData(): void
    {
        $connection = $this->manager->getConnection();
        $connection->executeQuery('DELETE FROM carpet_specific_treatment');
        $connection->executeQuery('DELETE FROM carpet_price_simulator');
        $connection->executeQuery('DELETE FROM carpet_price_base');
        $connection->executeQuery('DELETE FROM order_payment_detail');
        $connection->executeQuery('DELETE FROM carpet_order_detail');
        $connection->executeQuery('DELETE FROM `quote_detail`');
        $connection->executeQuery('DELETE FROM `quote`');
    }
    public function getQuoteRows(int $quoteId): array
    {
        $qb = $this->manager->createQueryBuilder();

        $qb->select('COUNT(qd.id) AS row_count, qd')
            ->from(QuoteDetail::class, 'qd')
            ->leftJoin('qd.quote', 'q')
            ->where('q.id = :quoteId')
            ->groupBy('qd.location')
            ->orderBy('qd.location', 'ASC')
            ->setParameter('quoteId', $quoteId);


        return $qb->getQuery()->getResult();
    }
}
