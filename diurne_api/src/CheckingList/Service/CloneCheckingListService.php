<?php

declare(strict_types=1);

namespace App\CheckingList\Service;

use App\CheckingList\Entity\CheckingList;
use App\CheckingList\Entity\ShapeValidation;
use App\CheckingList\Entity\QualityCheck;
use App\CheckingList\Entity\QualityRespect;
use App\CheckingList\Entity\LayersValidation;
use Doctrine\ORM\EntityManagerInterface;

class CloneCheckingListService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function cloneFrom(CheckingList $source, CheckingList $target): void
    {
        $this->cloneShapeValidation($source, $target);
        $this->cloneQualityCheck($source, $target);
        $this->cloneQualityRespect($source, $target);
        $this->cloneLayersValidation($source, $target);
    }

    private function cloneShapeValidation(CheckingList $source, CheckingList $target): void
    {
        $src = $this->getPropertyValue($source, 'shapeValidation');
        if (!$src) {
            return;
        }
        $new = new ShapeValidation();
        $this->copyEntity($src, $new, ['id', 'checkingList']);
        $new->setCheckingList($target);
        $target->setShapeValidation($new);
        $this->entityManager->persist($new);
    }

    private function cloneQualityCheck(CheckingList $source, CheckingList $target): void
    {
        $src = $this->getPropertyValue($source, 'qualityCheck');
        if (!$src) {
            return;
        }
        $new = new QualityCheck();
        $this->copyEntity($src, $new, ['id', 'checkingList']);
        $new->setCheckingList($target);
        $target->setQualityCheck($new);
        $this->entityManager->persist($new);
    }

    private function cloneQualityRespect(CheckingList $source, CheckingList $target): void
    {
        $src = $this->getPropertyValue($source, 'qualityRespect');
        if (!$src) {
            return;
        }
        $new = new QualityRespect();
        $this->copyEntity($src, $new, ['id', 'checkingList']);
        $new->setCheckingList($target);
        $target->setQualityRespect($new);
        $this->entityManager->persist($new);
    }

    private function cloneLayersValidation(CheckingList $source, CheckingList $target): void
    {
        $layers = $this->getPropertyValue($source, 'layersValidations');
        if (!$layers) {
            return;
        }
        foreach ($layers as $srcLayer) {
            $new = new LayersValidation();
            $this->copyEntity($srcLayer, $new, ['id', 'checkingList']);
            $new->setCheckingList($target);
            $target->addLayersValidation($new);
            $this->entityManager->persist($new);
        }
    }

    private function getPropertyValue(object $object, string $property)
    {
        $ref = new \ReflectionObject($object);
        if (!$ref->hasProperty($property)) {
            return null;
        }
        $prop = $ref->getProperty($property);
        $prop->setAccessible(true);
        if (method_exists($prop, 'isInitialized') && !$prop->isInitialized($object)) {
            return null;
        }
        return $prop->getValue($object);
    }

    private function copyEntity(object $source, object $dest, array $exclude = []): void
    {
        $refSrc = new \ReflectionObject($source);
        $refDest = new \ReflectionObject($dest);
        foreach ($refSrc->getProperties() as $prop) {
            $name = $prop->getName();
            if (in_array($name, $exclude, true)) {
                continue;
            }
            $prop->setAccessible(true);
            if (method_exists($prop, 'isInitialized') && !$prop->isInitialized($source)) {
                continue;
            }
            if (!$refDest->hasProperty($name)) {
                continue;
            }
            $destProp = $refDest->getProperty($name);
            $destProp->setAccessible(true);
            $destProp->setValue($dest, $prop->getValue($source));
        }
    }
}
