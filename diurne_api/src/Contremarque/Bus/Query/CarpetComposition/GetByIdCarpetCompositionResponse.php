<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CarpetComposition;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\CarpetComposition;
use App\Contremarque\Entity\Thread;
use App\Contremarque\Entity\ThreadDetail;
use Doctrine\Common\Collections\Collection;

final readonly class GetByIdCarpetCompositionResponse implements QueryResponse
{
    public function __construct(private ?CarpetComposition $carpetComposition)
    {
    }

    public function toArray(): array
    {
        return $this->carpetComposition ? [
            'id' => $this->carpetComposition->getId(),
            'trame' => $this->carpetComposition->getTrame(),
            'threadCount' => $this->carpetComposition->getThreadCount(),
            'layerCount' => $this->carpetComposition->getLayerCount(),
            'threads' => $this->getThreads($this->carpetComposition->getThreads()),
        ] : [];
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
