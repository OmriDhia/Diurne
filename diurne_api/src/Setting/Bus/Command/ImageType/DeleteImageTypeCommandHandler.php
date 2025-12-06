<?php

namespace App\Setting\Bus\Command\ImageType;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\ImageType;
use App\Setting\Repository\ImageTypeRepository;

class DeleteImageTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly ImageTypeRepository $imagetypeRepository) {}

    public function __invoke(DeleteImageTypeCommand $command): ImageTypeResponse
    {
        $imagetype = $this->imagetypeRepository->find($command->id);
        if (!$imagetype) {
            throw new RuntimeException('ImageType not found', 404);
        }

        try {
            $this->imagetypeRepository->remove($imagetype);
            $this->imagetypeRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete imagetype: ' . $e->getMessage(), 0, $e);
        }

        return new ImageTypeResponse($imagetype);
    }
}
