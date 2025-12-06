<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetComposition\Layer;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\NotFoundException;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\LayerRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteLayerCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly LayerRepository $layerRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(DeleteLayerCommand $command): DeleteLayerCommandResponse
    {
        $carpetCompositionId = $command->getCarpetCompositionId();
        $layerIds = $command->getLayerIds();

        // Vérifie si les layers existent
        $layers = $this->layerRepository->findBy([
            'carpetComposition' => $carpetCompositionId,
            'id' => $layerIds,
        ]);

        if (count($layers) !== count($layerIds)) {
            throw new ResourceNotFoundException('Un ou plusieurs layers spécifiés sont introuvables.');
        }

        // Supprime les layers
        foreach ($layers as $layer) {
            $this->entityManager->remove($layer);
        }

        $this->entityManager->flush();

        // Retourne une réponse
        return new DeleteLayerCommandResponse($layerIds, 'Layers supprimés avec succès.');
    }
}
