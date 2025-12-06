<?php

namespace App\Setting\Bus\Command\Conversion;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Conversion;
use App\Setting\Repository\ConversionRepository;

class DeleteConversionCommandHandler implements CommandHandler
{
    public function __construct(private readonly ConversionRepository $conversionRepository) {}

    public function __invoke(DeleteConversionCommand $command): ConversionResponse
    {
        $conversion = $this->conversionRepository->find($command->id);
        if (!$conversion) {
            throw new RuntimeException('Conversion not found', 404);
        }

        try {
            $this->conversionRepository->remove($conversion);
            $this->conversionRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete conversion: ' . $e->getMessage(), 0, $e);
        }

        return new ConversionResponse($conversion);
    }
}
