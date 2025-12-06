<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\Mesurement;
use App\Contremarque\Repository\MesurementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMMesurementRepository extends DoctrineORMRepository implements MesurementRepository
{
    protected const ENTITY_CLASS = Mesurement::class;
    protected const ALIAS = 'mesurement';

    /**
     * DoctrineORMMesurementRepository constructor.
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
    }

    /**
     * @param object $mesurement
     *
     * @return void
     */
    public function update($mesurement, array $data)
    {
    }

    public function findRandomMesurement(Contremarque $contremarque)
    {
        // Get the total count of mesurements for the given Contremarque
        try {
            $count = $this->manager->createQueryBuilder()
                ->from(self::ENTITY_CLASS, 'mesurement')
                ->select('count(mesurement.id)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No mesurements available for the given Contremarque
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Retrieve the random mesurement
        $query = $this->query()
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
