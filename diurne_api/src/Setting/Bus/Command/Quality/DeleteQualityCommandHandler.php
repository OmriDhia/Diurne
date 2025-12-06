<?php

namespace App\Setting\Bus\Command\Quality;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Quality;
use App\Setting\Repository\QualityRepository;

class DeleteQualityCommandHandler implements CommandHandler
{
    public function __construct(private readonly QualityRepository $qualityRepository) {}

    public function __invoke(DeleteQualityCommand $command): QualityResponse
    {
        $quality = $this->qualityRepository->find($command->id);
        if (!$quality) {
            throw new RuntimeException('Quality not found', 404);
        }
        // Check dependencies before deletion
        if (!$quality->getCarpetSpecifications()->isEmpty()) {
            throw new RuntimeException('Cannot delete Quality: It is linked to at least one CarpetSpecification. Please remove the relation(s) first.');
        }

        if (!$quality->getSamples()->isEmpty()) {
            throw new RuntimeException('Cannot delete Quality: It is linked to at least one Sample. Please remove the relation(s) first.');
        }

        if (!$quality->getQualityTarifTextures()->isEmpty()) {
            throw new RuntimeException('Cannot delete Quality: It is linked to one or more QualityTarifTextures. Please remove them first.');
        }
        try {
            $this->qualityRepository->remove($quality);
            $this->qualityRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete quality: ' . $e->getMessage(), 0, $e);
        }

        return new QualityResponse($quality);
    }
}
