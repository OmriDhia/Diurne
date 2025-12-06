<?php

namespace App\Setting\Bus\Command\SpecialShape;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\SpecialShape;
use App\Setting\Repository\SpecialShapeRepository;

class DeleteSpecialShapeCommandHandler implements CommandHandler
{
    public function __construct(private readonly SpecialShapeRepository $specialshapeRepository) {}

    public function __invoke(DeleteSpecialShapeCommand $command): SpecialShapeResponse
    {
        $specialshape = $this->specialshapeRepository->find($command->id);
        if (!$specialshape) {
            throw new RuntimeException('SpecialShape not found', 404);
        }

        try {
            $this->specialshapeRepository->remove($specialshape);
            $this->specialshapeRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete specialshape: ' . $e->getMessage(), 0, $e);
        }

        return new SpecialShapeResponse($specialshape);
    }
}
