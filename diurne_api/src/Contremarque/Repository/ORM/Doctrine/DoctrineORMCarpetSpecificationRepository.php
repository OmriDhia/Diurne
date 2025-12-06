<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use DateTimeInterface;
use DateTimeImmutable;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCarpetSpecificationRepository extends DoctrineORMRepository implements CarpetSpecificationRepository
{
    protected const ENTITY_CLASS = CarpetSpecification::class;
    protected const ALIAS = 'carpetSpecification';

    /**
     * DoctrineORMCarpetSpecificationRepository constructor.
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
    /**
     * Generates the next unique carpet reference in the format 'cspYY-mmXXX'.
     * - 'csp' is the prefix.
     * - 'YY-mm' is the year and month (e.g., '25-03' for March 2025).
     * - 'XXX' is a three-digit sequential number (e.g., '001', '002', ...) for the given year and month.
     *
     * @param DateTimeInterface|null $date The date to base the reference on (defaults to current date if null)
     * @return string The next unique carpet reference
     */
    public function getNextReference(?DateTimeInterface $date = null): string
    {
        // Use the provided date or default to the current date
        $date ??= new DateTimeImmutable();

        // Format the year and month as YY-mm (e.g., '25-03' for March 2025)
        $yearMonth = $date->format('y-m'); // 'y' gives last two digits of year, 'm' gives two-digit month

        // Define the prefix for the reference
        $prefix = 'csp';

        // Create the pattern to match references for the given year and month
        // e.g., 'csp25-03%' to match 'csp25-03001', 'csp25-03002', etc.
        $referencePattern = sprintf('%s%s%%', $prefix, $yearMonth);

        // Count the number of existing specifications for this year and month
        $qb = $this->manager->createQueryBuilder()
            ->from(CarpetSpecification::class, 'cs')
            ->select('COUNT(cs.id)')
            ->where('cs.carpetReference LIKE :pattern')
            ->setParameter('pattern', $referencePattern);

        $count = (int) $qb->getQuery()->getSingleScalarResult();

        // Increment the count to get the next sequential number
        $sequentialNumber = $count + 1;

        // Format the sequential number as a three-digit string (e.g., '001', '002', ...)
        $formattedSequentialNumber = sprintf('%03d', $sequentialNumber);

        // Construct the next reference
        return sprintf('%s%s%s', $prefix, $yearMonth, $formattedSequentialNumber);
    }
}
