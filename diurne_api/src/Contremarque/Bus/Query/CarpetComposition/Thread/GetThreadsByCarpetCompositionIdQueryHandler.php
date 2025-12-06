<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CarpetComposition\Thread;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ThreadRepository;

/**
 * This class is responsible for handling the 'get thread by ID' query.
 */
final readonly class GetThreadsByCarpetCompositionIdQueryHandler implements QueryHandler
{
    /**
     * Constructor with ThreadRepository injection.
     *
     * @param ThreadRepository $threadRepository thread repository interface
     */
    public function __construct(
        private ThreadRepository $threadRepository
    ) {
    }

    /**
     * Handles the 'get thread by ID' query.
     *
     * @param GetThreadsByCarpetCompositionIdQuery $query the query object containing the thread ID
     *
     * @return ThreadsQueryResponse the response object with thread details
     */
    public function __invoke(GetThreadsByCarpetCompositionIdQuery $query): ThreadsQueryResponse
    {
        $threads = $this->threadRepository->findBy(['carpetComposition' => $query->carpetCompositionId()]);

        return new ThreadsQueryResponse($threads);
    }
}
