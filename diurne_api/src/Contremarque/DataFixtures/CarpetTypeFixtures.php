<?php

namespace App\Contremarque\DataFixtures;

use App\Contremarque\Entity\CarpetType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarpetTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $carpetType = new CarpetType();
        $carpetType->setName('Tapis');
        $manager->persist($carpetType);
        $carpetType = new CarpetType();
        $carpetType->setName('Echantillon');
        $manager->persist($carpetType);
        $manager->flush();
    }
}
