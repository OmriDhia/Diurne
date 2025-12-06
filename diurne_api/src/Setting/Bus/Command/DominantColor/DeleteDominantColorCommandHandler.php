<?php

namespace App\Setting\Bus\Command\DominantColor;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\DominantColor;
use App\Setting\Repository\DominantColorRepository;

class DeleteDominantColorCommandHandler implements CommandHandler
{
    public function __construct(private readonly DominantColorRepository $dominantcolorRepository) {}

    public function __invoke(DeleteDominantColorCommand $command): DominantColorResponse
    {
        $dominantcolor = $this->dominantcolorRepository->find($command->id);
        if (!$dominantcolor) {
            throw new RuntimeException('DominantColor not found', 404);
        }

        try {
            $this->dominantcolorRepository->remove($dominantcolor);
            $this->dominantcolorRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete dominantcolor: ' . $e->getMessage(), 0, $e);
        }

        return new DominantColorResponse($dominantcolor);
    }
}
