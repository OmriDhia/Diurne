<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Thread;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\ThreadRepository;
use App\Setting\Repository\DominantColorRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateThreadCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ThreadRepository $threadRepository,
        private readonly DominantColorRepository $dominantColorRepository,
    ) {}

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateThreadCommand $command): ThreadResponse
    {
        $thread = $this->threadRepository->find($command->getId());

        if (null === $thread) {
            throw new ResourceNotFoundException();
        }
        if (null !== $command->getThreadNumber()) {
            $thread->setThreadNumber($command->getThreadNumber());
        }
        if (null !== $command->getTechColorId()) {
            $techColor = $this->dominantColorRepository->find((int) $command->getTechColorId());
            $thread->setTechColor($techColor);
        }

        $this->threadRepository->save($thread, true);

        return new ThreadResponse($thread);
    }
}
