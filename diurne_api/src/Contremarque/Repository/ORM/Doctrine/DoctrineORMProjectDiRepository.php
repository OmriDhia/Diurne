<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use DateTimeInterface;
use DateTimeImmutable;
use RuntimeException;
use DateTime;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\ProjectDi;
use App\Contremarque\Repository\ProjectDiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMProjectDiRepository extends DoctrineORMRepository implements ProjectDiRepository
{
    protected const ENTITY_CLASS = ProjectDi::class;
    protected const ALIAS = 'project_di';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function findOneByDemandeNumber(string $demandeNumber): ?ProjectDi
    {
        try {
            $object = $this->query()
                ->where('contremarque.designation = :name')
                ->where('project_di.demandeNumber = :demande_number')
                ->setParameter('demande_number', $demandeNumber)
                ->getQuery()->getSingleResult();
        } catch (NoResultException | NonUniqueResultException) {
            return null;
        }

        return $object;
    }

    /**
     * @return null|scalar
     */
    public function countProjectDiForDate(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        try {
            $count = $this->manager->createQueryBuilder()
                ->select('COUNT(project_di.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->where('project_di.createdAt BETWEEN :start AND :end')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }

        return $count;
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

    public function generateProjectNumber(): string
    {
        $date = new DateTimeImmutable();
        $prefix = 'DI';
        $year = $date->format('y'); // e.g., "25" for 2025
        $month = $date->format('m'); // e.g., "03" for March

        // Get the count of ProjectDi records for the current year and month
        $startDate = $date->modify('first day of this month')->setTime(0, 0, 0);
        $endDate = $date->modify('last day of this month')->setTime(23, 59, 59);
        $count = $this->countProjectDiForDate($startDate, $endDate) + 1;

        // Generate the projectDiNumber and ensure uniqueness
        $maxAttempts = 1000; // Prevent infinite loops
        $attempt = 0;

        do {
            if ($count > 999) {
                throw new RuntimeException('Sequence number exceeded 999 for the current month. Please extend the sequence format or clear old records.');
            }

            $sequence = sprintf('%03d', $count); // e.g., "001", "002", etc.
            $projectDiNumber = sprintf('%s%s%s%s', $prefix, $year, $month, $sequence);

            // Check if the generated number already exists
            $existingProjectDi = $this->findOneByProjectDiNumber($projectDiNumber);
            if ($existingProjectDi === null) {
                return $projectDiNumber; // Unique number found
            }

            // If the number exists, increment the count and try again
            $count++;
            $attempt++;
        } while ($attempt < $maxAttempts);

        throw new RuntimeException('Unable to generate a unique projectDiNumber after ' . $maxAttempts . ' attempts.');
    }

    private function findOneByProjectDiNumber(string $projectDiNumber): ?ProjectDi
    {
        try {
            return $this->manager->createQueryBuilder()
                ->select('project_di')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->where('project_di.demande_number = :projectDiNumber')
                ->setParameter('projectDiNumber', $projectDiNumber)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException | NonUniqueResultException) {
            return null;
        }
    }

    /**
     * @return null|scalar
     */
    private function getProjectDiCountForDate(DateTimeInterface $date)
    {
        $startDate = new DateTime($date->format('Y-m-d 00:00:00'));
        $endDate = new DateTime($date->format('Y-m-d 23:59:59'));

        return $this->countProjectDiForDate($startDate, $endDate);
    }

    /**
     * @return ProjectDi[]
     */
    public function findByContremarqueId(int $contremarqueId): array
    {
        return $this->manager->createQueryBuilder()
            ->from(self::ENTITY_CLASS, 'p')
            ->andWhere('p.contremarque = :contremarqueId')
            ->setParameter('contremarqueId', $contremarqueId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get ProjectDi entities by Contremarque for a specific designer where it is assigned.
     *
     * @param int $contremarqueId the ID of the Contremarque entity
     * @param int $designerId     the ID of the Designer (User entity)
     *
     * @return ProjectDi[]
     */
    public function findByContremarqueAndDesigner(int $contremarqueId, int $designerId): array
    {
        $qb = $this->manager->createQueryBuilder();

        $qb->select('p')
            ->from(self::ENTITY_CLASS, 'p')
            ->innerJoin('p.contremarque', 'c')
            ->innerJoin('p.carpetDesignOrders', 'cd')
            ->innerJoin('cd.designers', 'd')
            ->where('c.id = :contremarqueId')
            ->andWhere('p.transmitted_to_studio = :transmitted_to_studio')
            ->andWhere('d.designer = :designerId')
            ->setParameter('contremarqueId', $contremarqueId)
            ->setParameter('transmitted_to_studio', true)
            ->setParameter('designerId', $designerId);

        return $qb->getQuery()->getResult();
    }
}
