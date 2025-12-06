<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\TransportConditionLang;
use App\Setting\Repository\TransportConditionLangRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMTransportConditionLangRepository extends DoctrineORMRepository implements TransportConditionLangRepository
{
    protected const ENTITY_CLASS = TransportConditionLang::class;
    protected const ALIAS = 'transportConditionLang';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(TransportConditionLang $transportConditionLang, $flush = false): void
    {
        $this->manager->persist($transportConditionLang);

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
