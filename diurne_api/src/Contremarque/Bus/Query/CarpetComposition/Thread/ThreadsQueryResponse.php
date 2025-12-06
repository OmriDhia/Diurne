<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CarpetComposition\Thread;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\Thread;
use App\Contremarque\Entity\ThreadDetail;

class ThreadsQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $threads)
    {
    }

    public function toArray(): array
    {
        /* @var Thread $thread */
        return array_map(fn($thread) => [
            'id' => $thread->getId(),
            'threadNumber' => $thread->getThreadNumber(),
            'techColor' => !empty($thread->getTechColor()) ? $thread->getTechColor() : $thread->getTechColor()->toArray(),
            'thread_details' => $this->getThreadDetails($thread->getThreadDetails()),
        ], $this->threads);
    }

    /**
     * @return array[]
     *
     * @psalm-return list{0?: array{id: mixed, color_id: mixed, material_id: mixed, pourcentage: mixed},...}
     */
    private function getThreadDetails($getThreadDetails): array
    {
        $threadDetails = [];
        /**
         * @var ThreadDetail $threadDetail
         */
        foreach ($getThreadDetails as $threadDetail) {
            $threadDetails[] = [
                'id' => $threadDetail->getId(),
                'color_id' => $threadDetail->getColor()->getId(),
                'material_id' => $threadDetail->getMaterial()->getId(),
                'pourcentage' => $threadDetail->getPourcentage(),
            ];
        }

        return $threadDetails;
    }
}
