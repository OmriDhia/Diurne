<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpetByRnNumber;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\CarpetRepository;

class GetCarpetByRnNumberQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly CarpetRepository $repository
    ) {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetCarpetByRnNumberQuery $query): CarpetResponse
    {
        $carpet = $this->repository->find($query->getCarpetId());
        if ($carpet === null) {
            throw new ResourceNotFoundException();
        }

        return new CarpetResponse($carpet);
    }
}
