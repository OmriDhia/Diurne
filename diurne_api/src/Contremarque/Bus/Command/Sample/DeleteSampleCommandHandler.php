<?php

namespace App\Contremarque\Bus\Command\Sample;

use Exception;
use RuntimeException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\Sample;
use App\Contremarque\Repository\SampleRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteSampleCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SampleRepository $sampleRepository
    ) {}

    /**
     * @throws NotFoundHttpException If the sample is not found
     * @throws Exception If the deletion fails
     */
    public function __invoke(DeleteSampleCommand $command): void
    {
        // Fetch the Sample entity
        $sample = $this->sampleRepository->find($command->id);
        if (!$sample) {
            throw new NotFoundHttpException("Sample with ID {$command->id} not found");
        }

        // Remove the sample within a transaction
        $this->sampleRepository->getEntityManager()->beginTransaction();
        try {
            $this->sampleRepository->remove($sample);
            $this->sampleRepository->flush();
            $this->sampleRepository->getEntityManager()->commit();
        } catch (Exception $e) {
            $this->sampleRepository->getEntityManager()->rollback();
            throw new RuntimeException('Failed to delete sample: ' . $e->getMessage(), 0, $e);
        }
    }
}
