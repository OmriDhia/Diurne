<?php

namespace App\Contremarque\Bus\Command\ImageCommand;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\ImageCommandRepository;

class DeleteImageCommandCommandHandler implements CommandHandler
{
    public function __construct(private readonly ImageCommandRepository $imageCommandRepository)
    {
    }

    public function __invoke(DeleteImageCommandCommand $command)
    {
        $imageCommand = $this->imageCommandRepository->find($command->getId());
        if (!$imageCommand) {
            throw new InvalidArgumentException('Image command not found');
        }
        foreach ($imageCommand->getTechnicalImages() as $technicalImage) {
            $imageCommand->removeTechnicalImage($technicalImage);
        }
        foreach ($imageCommand->getImageCommandDesignerAssignments() as $assignment) {
            $imageCommand->removeImageCommandDesignerAssignment($assignment);
        }
        $this->imageCommandRepository->remove($imageCommand);
        $this->imageCommandRepository->flush();
    }

}