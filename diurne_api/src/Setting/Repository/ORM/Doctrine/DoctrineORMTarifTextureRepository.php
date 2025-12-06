<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\TarifTexture;
use App\Setting\Repository\TarifTextureRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMTarifTextureRepository extends DoctrineORMRepository implements TarifTextureRepository
{
    protected const ENTITY_CLASS = TarifTexture::class;
    protected const ALIAS = 'tarifGroup';

    /**
     * DoctrineORMTarifTextureRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function findOneByYear(string $year): ?TarifTexture
    {
        return $this->findOneBy(['year' => $year]);
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
