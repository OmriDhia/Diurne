<?php

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\OrderPayment\OrderPayment;
use App\Contremarque\Repository\OrderPaymentRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMOrderPaymentRepository extends DoctrineORMRepository implements OrderPaymentRepository
{
    protected const ENTITY_CLASS = OrderPayment::class;
    protected const ALIAS = 'order_payment';

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
