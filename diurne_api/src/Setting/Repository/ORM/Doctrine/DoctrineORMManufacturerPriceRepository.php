<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\ManufacturerPrice;
use App\Setting\Entity\ManufacturerPriceGrid;
use App\Setting\Entity\Material;
use App\Setting\Repository\ManufacturerPriceRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMManufacturerPriceRepository extends DoctrineORMRepository implements ManufacturerPriceRepository
{
    private const ENTITY_CLASS = ManufacturerPrice::class;

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, 'mp');
    }

    public function create(array $data): object
    {
        $manufacturerPrice = new ManufacturerPrice();

        if (isset($data['priceGrid'])) {
            $manufacturerPrice->setManufacturerPriceGrid($data['priceGrid']);
        }

        if (isset($data['material'])) {
            $manufacturerPrice->setMaterial($data['material']);
        }

        if (isset($data['price'])) {
            $manufacturerPrice->setPrice($data['price']);
        }

        if (isset($data['effectiveDate'])) {
            $manufacturerPrice->setEffectiveDate($data['effectiveDate']);
        }

        $this->manager->persist($manufacturerPrice);

        if (($data['flush'] ?? false) === true) {
            $this->manager->flush();
        }

        return $manufacturerPrice;
    }

    public function update($entity, array $data): void
    {
        if (!$entity instanceof ManufacturerPrice) {
            throw new \InvalidArgumentException('Entity must be an instance of ManufacturerPrice');
        }

        if (isset($data['priceGrid'])) {
            $entity->setManufacturerPriceGrid($data['priceGrid']);
        }

        if (isset($data['material'])) {
            $entity->setMaterial($data['material']);
        }

        if (isset($data['price'])) {
            $entity->setPrice($data['price']);
        }

        if (isset($data['effectiveDate'])) {
            $entity->setEffectiveDate($data['effectiveDate']);
        }

        $this->manager->persist($entity);

        if (($data['flush'] ?? false) === true) {
            $this->manager->flush();
        }
    }

    public function findOneByGridAndMaterial(ManufacturerPriceGrid $priceGrid, Material $material): ?ManufacturerPrice
    {
        return $this->findOneBy([
            'manufacturerPriceGrid' => $priceGrid,
            'material' => $material,
        ]);
    }
}
