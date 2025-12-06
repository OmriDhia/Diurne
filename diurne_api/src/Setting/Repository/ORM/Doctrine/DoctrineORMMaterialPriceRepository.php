<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\MaterialPrice;
use App\Setting\Entity\Material;
use App\Setting\Entity\QualityTarifTexture;
use App\Setting\Repository\MaterialPriceRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMMaterialPriceRepository extends DoctrineORMRepository implements MaterialPriceRepository
{
    protected const ENTITY_CLASS = MaterialPrice::class;
    protected const ALIAS = 'materialPrice';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(MaterialPrice $materialPrice, $flush = false): void
    {
        $this->manager->persist($materialPrice);

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

    public function findOneByQualityTarifTextureAndMaterial(QualityTarifTexture $qualityTarifTexture, Material $material): ?MaterialPrice
    {
        return $this->findOneBy([
            'qualityTarifTexture' => $qualityTarifTexture,
            'material' => $material,
        ]);
    }
}
