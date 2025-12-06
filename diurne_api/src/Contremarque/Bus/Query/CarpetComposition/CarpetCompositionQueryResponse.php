<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CarpetComposition;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\CarpetComposition;
use App\Contremarque\Entity\Thread;
use App\Contremarque\Entity\ThreadDetail;
use Doctrine\Common\Collections\Collection;

class CarpetCompositionQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $carpetCompositions)
    {
    }

    public function toArray(): array
    {
        /* @var CarpetComposition $carpetComposition */
        return array_map(fn($carpetComposition) => [
            'id' => $carpetComposition->getId(),
            'trame' => $carpetComposition->getTrame(),
            'threadCount' => $carpetComposition->getThreadCount(),
            'layerCount' => $carpetComposition->getLayerCount(),
            'threads' => $this->getThreads($carpetComposition->getThreads()),
        ], $this->carpetCompositions);
    }

    private function getThreads(Collection $threads): array
    {
        $threadsArray = [];
        /** @var Thread $thread */
        foreach ($threads as $thread) {
            $threadsArray[] = [
                'id' => $thread->getId(),
                'threadNumber' => $thread->getThreadNumber(),
                'techColorHex' => $thread->getTechColorHex(),
                'thread_details' => $this->getThreadDetails($thread->getThreadDetails()),
            ];
        }

        return $threadsArray;
    }

    private function getThreadDetails(Collection $getThreadDetails): array
    {
        $threadDetails = [];
        /* @var ThreadDetail $thread */
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
