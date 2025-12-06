<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetTarifExpeditions;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\TarifExpeditionRepository;

class GetTarifExpeditionsQueryHandler implements QueryHandler
{
    /**
     * @param TarifExpeditionRepository $repository
     */
    public function __construct(private readonly TarifExpeditionRepository $repository)
    {
    }

    /**
     * @param GetTarifExpeditionsQuery $query
     * @return GetTarifExpeditionsResponse
     */
    public function __invoke(GetTarifExpeditionsQuery $query): GetTarifExpeditionsResponse
    {
        $list = $this->repository->findAll();

        return new GetTarifExpeditionsResponse($list);
    }
}
