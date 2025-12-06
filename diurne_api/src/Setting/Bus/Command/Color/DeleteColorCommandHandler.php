<?php

namespace App\Setting\Bus\Command\Color;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Color;
use App\Setting\Repository\ColorRepository;

class DeleteColorCommandHandler implements CommandHandler
{
    public function __construct(private readonly ColorRepository $colorRepository) {}

    public function __invoke(DeleteColorCommand $command): ColorResponse
    {
        $color = $this->colorRepository->find($command->id);
        if (!$color) {
            throw new RuntimeException('Color not found', 404);
        }

        try {
            $this->colorRepository->remove($color);
            $this->colorRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete color: ' . $e->getMessage(), 0, $e);
        }

        return new ColorResponse($color);
    }
}
