<?php

namespace App\Contremarque\Bus\Command\ImageCommandDesignerAssignment;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\ImageCommandDesignerAssignmentRepository;

class DeleteImageCommandDesignerAssignmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly ImageCommandDesignerAssignmentRepository $imageCommandDesignerAssignmentRepository)
    {
    }

    public function __invoke(DeleteImageCommandDesignerAssignmentCommand $command): void
    {
        $icda = $this->imageCommandDesignerAssignmentRepository->find($command->getId());
        if (!$icda) {
            throw new InvalidArgumentException('Image command not found');
        }
        $this->imageCommandDesignerAssignmentRepository->remove($icda);
        $this->imageCommandDesignerAssignmentRepository->flush();
    }
}