<?php

namespace App\Contremarque\Bus\Query\GetAllSamplesByCarpetDesignOrder;

use App\Contremarque\Entity\Sample;
use Doctrine\ORM\EntityManagerInterface;
use App\Contremarque\Bus\Command\Sample\SampleResponse;
use App\Common\Bus\Query\QueryHandler;

class GetAllSamplesByCarpetDesignOrderQueryHandler implements QueryHandler
{
    private const VALID_ORDER_COLUMNS = [
        'id' => 's.id',
        'diCommandNumber' => 's.diCommandNumber',
        'createdAt' => 's.createdAt',
        'updatedAt' => 's.updatedAt',
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(GetAllSamplesByCarpetDesignOrderQuery $query): GetAllSamplesByCarpetDesignOrderResponse
    {
        $orderBy = $query->getOrderBy() ?? 'id';
        $orderWay = strtoupper($query->getOrderWay() ?? 'DESC');

        $orderColumn = self::VALID_ORDER_COLUMNS[$orderBy] ?? self::VALID_ORDER_COLUMNS['id'];
        if (!in_array($orderWay, ['ASC', 'DESC'])) {
            $orderWay = 'DESC';
        }

        $offset = ($query->getPage() - 1) * $query->getItemsPerPage();
        $samples = $this->entityManager->getRepository(Sample::class)->createQueryBuilder('s')
            ->where('s.carpetDesignOrder = :carpetDesignOrderId')
            ->setParameter('carpetDesignOrderId', $query->getCarpetDesignOrderId())
            ->orderBy($orderColumn, $orderWay)
            ->setFirstResult($offset)
            ->setMaxResults($query->getItemsPerPage())
            ->getQuery()
            ->getResult();

        $totalCount = $this->entityManager->getRepository(Sample::class)->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->where('s.carpetDesignOrder = :carpetDesignOrderId')
            ->setParameter('carpetDesignOrderId', $query->getCarpetDesignOrderId())
            ->getQuery()
            ->getSingleScalarResult();

        $responses = array_map(fn(Sample $sample) => new SampleResponse($sample), $samples);

        return new GetAllSamplesByCarpetDesignOrderResponse(
            (int) $totalCount,
            $query->getPage(),
            $query->getItemsPerPage(),
            $responses
        );
    }
}
