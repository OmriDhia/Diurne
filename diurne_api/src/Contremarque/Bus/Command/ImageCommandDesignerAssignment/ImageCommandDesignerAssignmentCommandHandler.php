<?php

namespace App\Contremarque\Bus\Command\ImageCommandDesignerAssignment;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\ImageCommand\ImageCommandDesignerAssignment;
use App\Contremarque\Repository\ImageCommandDesignerAssignmentRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use App\User\Repository\UserRepository;

class ImageCommandDesignerAssignmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly ImageCommandDesignerAssignmentRepository $imageCommandDesignerAssignmentRepository, private readonly ImageCommandRepository $imageCommandRepository, private readonly UserRepository $userRepository)
    {
    }

    public function __invoke(ImageCommandDesignerAssignmentCommand $command): ImageCommandDesignerAssignmentResponse
    {
        $imageCommand = $this->imageCommandRepository->find($command->getImageCommandId());
        $designer = $this->userRepository->find($command->getDesignerId());
        if (null === $imageCommand) {
            throw new Exception('Image command not found');
        }
        if (null === $designer) {
            throw new Exception('Designer not found');
        }
        if ('Designer' !== $designer->getProfile()->getName()) {
            throw new Exception('User is not a designer');
        }
        $imageCommandDesignerAssignment = new ImageCommandDesignerAssignment();
        $imageCommandDesignerAssignment->setImageCommand($imageCommand);
        $imageCommandDesignerAssignment->setDesigner($designer);
        $imageCommandDesignerAssignment->setFromDatetime($command->getFrom());
        $imageCommandDesignerAssignment->setToDatetime($command->getTo());
        $imageCommandDesignerAssignment->setInProgress($command->getInProgress());
        $imageCommandDesignerAssignment->setStopped($command->getStopped());
        $imageCommandDesignerAssignment->setReasonForStopping($command->getReasonForStopping());
        $imageCommandDesignerAssignment->setDone($command->getDone());
        $this->imageCommandDesignerAssignmentRepository->persist($imageCommandDesignerAssignment);
        $this->imageCommandDesignerAssignmentRepository->flush();

        return new ImageCommandDesignerAssignmentResponse($imageCommandDesignerAssignment);
    }
}
