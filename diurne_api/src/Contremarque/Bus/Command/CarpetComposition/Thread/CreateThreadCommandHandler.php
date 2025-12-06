<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Thread;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\Thread;
use App\Contremarque\Repository\CarpetCompositionRepository;
use App\Contremarque\Repository\ThreadRepository;
use App\Setting\Repository\DominantColorRepository;

class CreateThreadCommandHandler implements CommandHandler
{
    public function __construct(private readonly ThreadRepository $threadRepository, private readonly CarpetCompositionRepository $carpetCompositionRepository, private readonly DominantColorRepository $dominantColorRepository)
    {
    }

    public function __invoke(CreateThreadCommand $command): ThreadResponse
    {
        $carpetComposition = $this->carpetCompositionRepository->find($command->getCarpetCompositionId());
        if (!$carpetComposition) {
            throw new Exception('Carpet composition not found');
        }
        $thread = new Thread();
        $thread->setThreadNumber($command->getThreadNumber());

        $techColor = $this->dominantColorRepository->find((int) $command->getTechColorId());
        $thread->setTechColor($techColor);
        $thread->setCarpetComposition($carpetComposition);

        $this->threadRepository->save($thread, true);

        return new ThreadResponse($thread);
    }
}
