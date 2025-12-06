<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use DomainException;
use DateTime;
use App\Contact\Entity\ContactCommercialHistory;
use App\Contact\Entity\ContactInformationSheet;
use DateTimeImmutable;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Repository\ContremarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMContremarqueRepository extends DoctrineORMRepository implements ContremarqueRepository
{
    protected const ENTITY_CLASS = Contremarque::class;
    protected const ALIAS = 'contremarque';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function findOneByName($name): ?Contremarque
    {
        try {
            $object = $this->query()
                ->where('contremarque.designation = :name')
                ->setParameter('name', $name)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }

    public function findOneByNumber($number): ?Contremarque
    {
        try {
            $object = $this->query()
                ->where('contremarque.project_number = :project_number')
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

    public function getNextProjectNumber(): string
    {
        $date = new DateTime();
        $prefix = 'PR';
        $year = $date->format('y');
        $month = $date->format('m');

        $sql = 'SELECT COUNT(id) FROM contremarque WHERE MONTH(created_at) = :month AND YEAR(created_at) = :year';
        $stmt = $this->manager->getConnection()->prepare($sql);
        $last = $stmt->execute(['month' => $month, 'year' => $date->format('Y')])->fetchOne();

        $count = (int) $last + 1;

        return sprintf('%s%s%s%02d', $prefix, $year, $month, $count);
    }

    public function findByFilters(array $filters, int $page, int $limit, string $orderBy, string $orderWay, bool $countOnly = false)
    {
        $qb = $this->query();
        $qb->join('contremarque.customer', 'cc');
        $qb->leftJoin(ContactCommercialHistory::class, 'cch', 'WITH', 'cch.customer = cc.id');
        $qb->join('contremarque.prescriber', 'pp');
        $qb->leftJoin(ContactInformationSheet::class, 'cis', 'WITH', 'pp.contactInformationSheet = cis.id');
        $qb->leftJoin('cch.commercial', 'commercial');

        if ($countOnly) {
            $qb->select('COUNT(DISTINCT contremarque.id)');
        } else {
            $qb->select('contremarque, cc.socialReason as customer_name, CONCAT(commercial.firstname, \' \', commercial.lastname) as commercial_name, CONCAT(cis.firstname, \' \', cis.lastname) as prescripteur');
        }

        $this->applyFilters($qb, $filters);

        // Ordering and pagination
        if (!$countOnly) {
            $this->applyOrdering($qb, $orderBy, $orderWay);

            $qb->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit);
        }

        $results = $qb->getQuery()->getResult();
        if (!$countOnly) {
            $this->populateLastEvent($results, $filters);
        }

        return $countOnly ? (int) $results[0][1] : $results;
    }

    private function applyFilters($qb, array $filters): void
    {
        if (!empty($filters['designation'])) {
            $qb->andWhere('contremarque.designation LIKE :designation')
                ->setParameter('designation', '%' . $filters['designation'] . '%');
        }

        if (!empty($filters['customerId'])) {
            $qb->andWhere('contremarque.customer = :customerId')
                ->setParameter('customerId', $filters['customerId']);
        }

        if (!empty($filters['commercialId'])) {
            $qb->andWhere('contremarque.commercialId = :commercialId')
                ->setParameter('commercialId', $filters['commercialId']);
        }

        if (!empty($filters['commercial'])) {
            $subQuery = $this->createCommercialSubQuery('cch_sub');

            $qb->andWhere(
                $qb->expr()->exists(
                    $this->manager->createQueryBuilder()
                        ->select('1')
                        ->from(ContactCommercialHistory::class, 'cch2')
                        ->where('cch2.customer = cc.id')
                        ->andWhere('cch2.commercial = commercial.id')
                        ->andWhere('cch2.fromDate = (' . $subQuery . ')')
                        ->andWhere(
                            $qb->expr()->like(
                                $qb->expr()->concat('commercial.firstname', $qb->expr()->literal(' '), 'commercial.lastname'),
                                ':commercial'
                            )
                        )
                        ->getDQL()
                )
            )
                ->setParameter('commercial', '%' . $filters['commercial'] . '%');
        }

        if (!empty($filters['creationDate'])) {
            $qb->andWhere('contremarque.createdAt >= :creationDate')
                ->setParameter('creationDate', $filters['creationDate']);
        }

        if (!empty($filters['targetDate'])) {
            $targetDate = new DateTimeImmutable($filters['targetDate']);
            $qb->andWhere('contremarque.target_date BETWEEN :startOfDay AND :endOfDay')
                ->setParameter('startOfDay', $targetDate->format('Y-m-d 00:00:00'))
                ->setParameter('endOfDay', $targetDate->format('Y-m-d 23:59:59'));
        }

        if (!empty($filters['prescripteur'])) {
            $qb->andWhere(
                'CONCAT(cis.firstname, \' \', cis.lastname) LIKE :prescripteur'
            )
                ->setParameter('prescripteur', '%' . $filters['prescripteur'] . '%');
        }

        if (!empty($filters['customerName'])) {
            $qb->andWhere('cc.socialReason LIKE :customerName')
                ->setParameter('customerName', '%' . $filters['customerName'] . '%');
        }
    }

    private function applyOrdering($qb, string $orderBy, string $orderWay): void
    {
        match ($orderBy) {
            'socialReason' => $qb->orderBy('cc.socialReason', $orderWay),
            'commercialName' => $qb->orderBy(
                $qb->expr()->concat('commercial.firstname', $qb->expr()->literal(' '), 'commercial.lastname'),
                $orderWay
            ),
            default => $qb->orderBy("contremarque.$orderBy", $orderWay),
        };
    }

    private function createCommercialSubQuery(string $alias): string
    {
        return $this->manager->createQueryBuilder()
            ->select('MAX(' . $alias . '.fromDate)')
            ->from(ContactCommercialHistory::class, $alias)
            ->where($alias . '.customer = cc.id')
            ->andWhere($alias . '.status IS NOT NULL')
            ->getDQL();
    }

    private function populateLastEvent(array &$results, $filters): void
    {
        $conn = $this->manager->getConnection();
        foreach ($results as $index => &$result) {
            $contremarqueId = $result[0]->getId(); // Ensure 'id' is the correct field
            $sql = 'SELECT e.*, en.subject FROM event e
            INNER JOIN event_nomenclature en ON(e.nomenclature_id=en.id)
            WHERE e.contremarque_id = :contremarqueId ORDER BY e.event_date DESC LIMIT 1';
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute(['contremarqueId' => $contremarqueId])->fetchAssociative();
            $result['last_event'] = $res;

            if (!empty($filters['relaunchExceeded'])) {
                if (!empty($res['next_reminder_deadline']) && 1 !== (int) $res['reminder_disabled']) {
                    $nexReminder = new DateTime($res['next_reminder_deadline']);
                    if ($nexReminder > new DateTime()) {
                        unset($results[$index]);
                    }
                }
            }

            if (!empty($filters['relaunchExceededByWeek'])) {
                if (!empty($res['next_reminder_deadline']) && 1 !== (int) $res['reminder_disabled']) {
                    $nexReminder = new DateTime($res['next_reminder_deadline']);
                    $nextWeek = new DateTime('+1 week');

                    if ($nexReminder > $nextWeek || $nexReminder < new DateTime()) {
                        unset($results[$index]);
                    }
                }
            }

            if (!empty($filters['withoutRelaunch'])) {
                if (!empty($res['next_reminder_deadline']) && 1 !== (int) $res['reminder_disabled']) {
                    unset($results[$index]);
                }
            }
            if (!empty($filters['isCurrentProject'])) {
                if (!empty($res['next_reminder_deadline']) && 1 !== (int) $res['reminder_disabled']) {
                    $nexReminder = new DateTime($res['next_reminder_deadline']);
                    if ($nexReminder < new DateTime()) {
                        unset($results[$index]);
                    }
                }
            }
        }
    }

    public function findOneById(int $id): ?Contremarque
    {
        return $this->find($id);
    }

    public function findOneByIdWithRelations(int $id): ?Contremarque
    {
        $qb = $this->query();
        $qb->leftJoin('contremarque.customer', 'customer');
        $qb->leftJoin('customer.contactCommercialHistories', 'cch');
        $qb->leftJoin('cch.commercial', 'commercial');
        $qb->leftJoin('cch.status', 'status');
        $qb->where('contremarque.id = :id')
            ->setParameter('id', $id);

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException) {
            return null;
        }
    }
}
