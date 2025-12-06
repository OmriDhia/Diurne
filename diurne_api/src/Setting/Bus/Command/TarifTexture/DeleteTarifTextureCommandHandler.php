<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\TarifTexture;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Repository\TarifTextureRepository;

class DeleteTarifTextureCommandHandler implements CommandHandler
{
    public function __construct(private readonly TarifTextureRepository $tarifTextureRepository)
    {
    }

    public function __invoke(DeleteTarifTextureCommand $command): TarifTextureResponse
    {
        $entity = $this->tarifTextureRepository->find($command->id);
        if (!$entity) {
            throw new RuntimeException('Tarif texture not found', 404);
        }

        try {
            $this->tarifTextureRepository->remove($entity);
            $this->tarifTextureRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete tarif texture: ' . $e->getMessage(), 0, $e);
        }

        return new TarifTextureResponse($entity);
    }
}

