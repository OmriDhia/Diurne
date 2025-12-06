<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetComposition;
use App\Contremarque\Repository\CarpetCompositionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCarpetCompositionRepository extends DoctrineORMRepository implements CarpetCompositionRepository
{
    protected const ENTITY_CLASS = CarpetComposition::class;
    protected const ALIAS = 'carpetComposition';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(CarpetComposition $carpetComposition, $flush = false): void
    {
        $this->manager->persist($carpetComposition);

        if ($flush) {
            $this->manager->flush();
        }
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
