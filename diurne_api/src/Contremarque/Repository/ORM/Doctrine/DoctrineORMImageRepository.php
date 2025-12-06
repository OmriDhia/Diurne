<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\DTO\Image\GetImagesQueryDTO;
use App\Contremarque\Entity\Image;
use App\Contremarque\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;


class DoctrineORMImageRepository extends DoctrineORMRepository implements ImageRepository
{
    protected const ENTITY_CLASS = Image::class;
    protected const ALIAS = 'image';

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

    public function getNextImageNumber(): string
    {
        $prefix = 'HC';

        try {
            // Query to find the last image reference that starts with 'HC'
            $lastImageReference = $this->manager->createQueryBuilder()
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->select(self::ALIAS . '.image_reference')
                ->where(self::ALIAS . '.image_reference LIKE :prefix')
                ->setParameter('prefix', $prefix . '%')
                ->orderBy(self::ALIAS . '.image_reference', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();

            // Extract the numeric part of the last reference (after the prefix)
            $lastNumber = (int)substr($lastImageReference, strlen($prefix));
        } catch (NoResultException | NonUniqueResultException) {
            // If there are no results, start from 0
            $lastNumber = 0;
        }

        // Increment the number
        $nextNumber = $lastNumber + 1;

        // Generate the new image reference with leading zeros
        $nextImageReference = sprintf('%s%06d', $prefix, $nextNumber);

        return $nextImageReference;
    }

    public function deleteById(int $id): void
    {
        $this->manager->createQueryBuilder()
            ->delete(Image::class, 'img')
            ->where('img.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    /**
     * @return Image[]
     */
    public function findByFilters(GetImagesQueryDTO $dto): array
    {
        $qb = $this->manager->createQueryBuilder()
            ->from(self::ENTITY_CLASS, 'i')
            ->select('i.id, i.image_reference, it.name as type, i.isValidated, i.commentaire, c.id as idContremarque, pdi.id as idDi, l.id as idLocation')
            ->leftJoin('i.imageType', 'it')
            ->leftJoin('i.carpetDesignOrder', 'cdo')
            ->leftJoin('cdo.projectDi', 'pdi')
            ->leftJoin('pdi.contremarque', 'c')
            ->leftJoin('cdo.location', 'l');

        $this->applyFilters($qb, $dto);

        return $qb->getQuery()->getResult();
    }

    private function applyFilters(QueryBuilder $qb, GetImagesQueryDTO $dto): void
    {
        if ($dto->idContremarque !== null) {
            $qb->andWhere('c.id = :idContremarque')
                ->setParameter('idContremarque', $dto->idContremarque);
        }

        if ($dto->idDi !== null) {
            $qb->andWhere('pdi.id = :idDi')
                ->setParameter('idDi', $dto->idDi);
        }

        if ($dto->idLocation !== null) {
            $qb->andWhere('l.id = :idLocation')
                ->setParameter('idLocation', $dto->idLocation);
        }
    }
}
