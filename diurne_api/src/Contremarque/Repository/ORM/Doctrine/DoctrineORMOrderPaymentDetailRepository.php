<?php

namespace App\Contremarque\Repository\ORM\Doctrine;

use DateTime;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\OrderPayment\OrderPaymentDetail;
use App\Contremarque\Repository\OrderPaymentDetailRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMOrderPaymentDetailRepository extends DoctrineORMRepository implements OrderPaymentDetailRepository
{
    protected const ENTITY_CLASS = OrderPaymentDetail::class;
    protected const ALIAS = 'order_payment_detail';

    /**
     * DoctrineORMMesurementRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function softDeleteByOrderPayment(int $orderId): void
    {
        $paymentDetails = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->where(self::ALIAS . '.orderPayment = :orderId')
            ->andWhere(self::ALIAS . '.deleted = :deleted')
            ->setParameter('orderId', $orderId)
            ->setParameter('deleted', false)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null !== $paymentDetails) {
            $paymentDetails->setDeleted(true);
            $paymentDetails->setDeletedAt(new DateTime());

            $this->getEntityManager()->persist($paymentDetails);
            $this->getEntityManager()->flush();
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
