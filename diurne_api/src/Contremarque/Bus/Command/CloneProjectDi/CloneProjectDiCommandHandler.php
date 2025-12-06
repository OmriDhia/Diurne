<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CloneProjectDi;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\ProjectDi;
use App\Contremarque\Repository\ProjectDiRepository;
use App\Contremarque\Service\ProjectDi\ProjectDiCloner;

class CloneProjectDiCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ProjectDiRepository $projectDiRepository,
        private readonly ProjectDiCloner $cloner,
    ) {
    }

    public function __invoke(CloneProjectDiCommand $command): CloneProjectDiResponse
    {
        $original = $this->projectDiRepository->find($command->getProjectDiId());
        if (!$original instanceof ProjectDi) {
            throw new ResourceNotFoundException('ProjectDi not found.');
        }

        $cloned = $this->cloner->clone($original);

        $this->projectDiRepository->persist($cloned);
        $this->projectDiRepository->flush();

        return new CloneProjectDiResponse($cloned);
    }
}
