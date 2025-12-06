<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpetById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\CarpetRepository;

class GetCarpetByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly CarpetRepository $repository
    ) {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetCarpetByIdQuery $query): CarpetResponse
    {
        $carpet = $this->repository->find($query->carpetId);
        if ($carpet === null) {
            throw new ResourceNotFoundException();
        }

        return new CarpetResponse($carpet);
    }
}
