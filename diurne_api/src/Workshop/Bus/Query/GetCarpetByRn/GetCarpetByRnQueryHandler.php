<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpetByRn;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\CarpetRepository;

class GetCarpetByRnQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly CarpetRepository $repository
    ) {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetCarpetByRnQuery $query): CarpetResponse
    {
        $carpet = $this->repository->findOneBy(['rnNumber' => $query->getRnNumber()]);
        if ($carpet === null) {
            throw new ResourceNotFoundException();
        }

        return new CarpetResponse($carpet);
    }
}
