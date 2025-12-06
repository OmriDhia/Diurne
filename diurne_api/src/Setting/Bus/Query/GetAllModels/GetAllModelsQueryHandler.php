<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetAllModels;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\Model;
use App\Setting\Repository\ModelRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetAllModelsQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(
        private readonly ModelRepository $modelRepository
    )
    {
    }

    public function __invoke(GetAllModelsQuery $query): ModelQueryResponse
    {
        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $query->getItemsPerPage();

        if ($getAll) {
            $cacheKey = 'models_all';

            $modelsData = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapModels(),
                3600
            );

            $totalItems = count($modelsData);
        } else {
            $modelsData = $this->modelRepository->findBy([], null, $limit, $offset);
            $totalItems = $this->modelRepository->count([]);
        }

        return new ModelQueryResponse(
            $modelsData,
            $totalItems,
            $query->getPage(),
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * Fetches all Model entities and maps them to an array.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapModels(): array
    {
        $models = $this->modelRepository->findAll();
        $modelsData = [];

        foreach ($models as $model) {
            $modelsData[] = $this->mapModelToArray($model);
        }

        return $modelsData;
    }

    /**
     * Maps a Model entity to an array.
     *
     * @param Model $model
     * @return array<string, mixed>
     */
    private function mapModelToArray(Model $model): array
    {
        return $model->toArray();
    }
}
