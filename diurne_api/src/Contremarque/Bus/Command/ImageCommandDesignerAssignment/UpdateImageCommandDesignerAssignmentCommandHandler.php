<?php

namespace App\Contremarque\Bus\Command\ImageCommandDesignerAssignment;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\ImageCommandDesignerAssignmentRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use App\User\Repository\UserRepository;

class UpdateImageCommandDesignerAssignmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly ImageCommandDesignerAssignmentRepository $imageCommandDesignerAssignmentRepository, private readonly ImageCommandRepository $imageCommandRepository, private readonly UserRepository $userRepository)
    {
    }

    public function __invoke(UpdateImageCommandDesignerAssignmentCommand $command): ImageCommandDesignerAssignmentResponse
    {
        $icda = $this->imageCommandDesignerAssignmentRepository->find($command->getId());
        if (null === $icda) {
            throw new Exception('Image command designer assignment not found');
        }
        if (!empty($command->getImageCommandId())) {
            $imageCommand = $this->imageCommandRepository->find($command->getImageCommandId());
            if (null === $imageCommand) {
                throw new Exception('Image command not found');
            }
            $icda->setImageCommand($imageCommand);
        }
        if (!empty($command->getDesignerId())) {
            $designer = $this->userRepository->find($command->getDesignerId());
            if (null === $designer) {
                throw new Exception('Designer not found');
            }
            if ('Designer' !== $designer->getProfile()->getName()) {
                throw new Exception('User is not a designer');
            }
            $icda->setDesigner($designer);
        }
        if (!empty($command->getFrom())) {
            $icda->setFromDatetime($command->getFrom());
        }
        if (!empty($command->getTo())) {
            $icda->setToDatetime($command->getTo());
        }
        if (!empty($command->getInProgress())) {
            $icda->setInProgress($command->getInProgress());
        }
        if (!empty($command->getStopped())) {
            $icda->setStopped($command->getStopped());
        }
        if (!empty($command->getReasonForStopping())) {
            $icda->setReasonForStopping($command->getReasonForStopping());
        }
        if (!empty($command->getDone())) {
            $icda->setDone($command->getDone());
        }

        $this->imageCommandDesignerAssignmentRepository->persist($icda);
        $this->imageCommandDesignerAssignmentRepository->flush();

        return new ImageCommandDesignerAssignmentResponse($icda);
    }
}
