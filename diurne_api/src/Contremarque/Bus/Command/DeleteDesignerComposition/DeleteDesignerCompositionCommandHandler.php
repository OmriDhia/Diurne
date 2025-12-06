<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteDesignerComposition;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\DesignerCompositionRepository;

class DeleteDesignerCompositionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly DesignerCompositionRepository $designerCompositionRepository
    ) {
    }

    public function __invoke(DeleteDesignerCompositionCommand $command): void
    {
        $designerComposition = $this->designerCompositionRepository->find($command->getId());

        if (null === $designerComposition) {
            throw new ResourceNotFoundException('Designer Composition not found');
        }

        $this->designerCompositionRepository->remove($designerComposition);
        $this->designerCompositionRepository->flush();
    }
}
