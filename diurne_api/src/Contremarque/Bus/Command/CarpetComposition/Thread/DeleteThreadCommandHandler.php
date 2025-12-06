<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Thread;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\CarpetCompositionRepository;
use App\Contremarque\Repository\LayerDetailRepository;
use App\Contremarque\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteThreadCommandHandler implements CommandHandler
{
    public function __construct(private readonly ThreadRepository $threadRepository, private readonly EntityManagerInterface $em, private readonly CarpetCompositionRepository $carpetCompositionRepository, private readonly LayerDetailRepository $layerDetailsRepository)
    {
    }

    public function __invoke(DeleteThreadCommand $command): DeleteThreadResponse
    {
        // Find the carpet composition to ensure it exists
        $carpetComposition = $this->carpetCompositionRepository->find($command->getCarpetCompositionId());
        if (!$carpetComposition) {
            throw new Exception('Carpet composition not found');
        }

        // Find the thread by ID
        $thread = $this->threadRepository->find($command->getThreadId());
        if (!$thread) {
            throw new Exception('Thread not found');
        }

        $threadDetails = $this->layerDetailsRepository->getRelatedThreadDetails($command->getThreadId());
        if (count($threadDetails)) {
            foreach ($threadDetails as $threadDetail) {
                $this->em->remove($threadDetail);
            }
        }
        // Remove the thread
        $this->threadRepository->remove($thread);
        $this->em->flush();

        // Return a response confirming deletion
        return new DeleteThreadResponse($command->getThreadId());
    }
}
