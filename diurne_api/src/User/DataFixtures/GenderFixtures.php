<?php

namespace App\User\DataFixtures;

use App\User\Entity\Gender;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class GenderFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $genders = ['Mr', 'Mme', 'Mr et Mme'];

        foreach ($genders as $genderName) {
            $gender = new Gender();
            $gender->setName($genderName);
            $manager->persist($gender);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['user'];
    }
}
