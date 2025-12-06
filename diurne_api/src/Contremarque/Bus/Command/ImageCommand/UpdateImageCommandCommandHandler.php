<?php

namespace App\Contremarque\Bus\Command\ImageCommand;

use App\Contremarque\Repository\CarpetStatusRepository;
use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Contremarque\Repository\SampleRepository;

class UpdateImageCommandCommandHandler implements CommandHandler
{
    public function __construct(private readonly SampleRepository $sampleRepository, private readonly ImageCommandRepository $imageCommandRepository, private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository, private readonly CarpetStatusRepository $carpetStatusRepository)
    {
    }

    public function __invoke(UpdateImageCommandCommand $command): ImageCommandResponse
    {
        $imageCommand = $this->imageCommandRepository->find($command->getId());
        if (!$imageCommand) {
            throw new InvalidArgumentException('Image command not found');
        }

        if (!empty($command->getCommandNumber())) {
            $imageCommand->setCommandNumber($command->getCommandNumber());
        }
        if (!empty($command->getCommercialComment())) {
            $imageCommand->setCommercialComment($command->getCommercialComment());
        }
        if (!empty($command->getAdvComment())) {
            $imageCommand->setAdvComment($command->getAdvComment());
        }
        if (!empty($command->getRn())) {
            $imageCommand->setRn($command->getRn());
        }
        if (!empty($command->getStudioComment())) {
            $imageCommand->setStudioComment($command->getStudioComment());
        }
        if (null !== $command->getStatusId()) {
            $status = $this->carpetStatusRepository->find((int)$command->getStatusId());
            $imageCommand->setStatus($status);
        }
        $this->imageCommandRepository->persist($imageCommand);
        $this->imageCommandRepository->flush();
        return new ImageCommandResponse($imageCommand);
    }
}
