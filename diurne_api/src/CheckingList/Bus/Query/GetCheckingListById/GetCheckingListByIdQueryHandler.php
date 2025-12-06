<?php

namespace App\CheckingList\Bus\Query\GetCheckingListById;

use App\CheckingList\Repository\CheckingListRepository;
use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;

class GetCheckingListByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly CheckingListRepository $repository)
    {
    }

    public function __invoke(GetCheckingListByIdQuery $query): GetCheckingListByIdResponse
    {
        $list = $this->repository->find($query->id);
        if (!$list) {
            throw new ResourceNotFoundException();
        }

        return new GetCheckingListByIdResponse($list);
    }
}
