<?php

namespace App\Setting\Bus\Command\Carrier;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Carrier;
use App\Setting\Repository\CarrierRepository;

class DeleteCarrierCommandHandler implements CommandHandler
{
    public function __construct(private readonly CarrierRepository $carrierRepository) {}

    public function __invoke(DeleteCarrierCommand $command): CarrierResponse
    {
        $carrier = $this->carrierRepository->find($command->id);
        if (!$carrier) {
            throw new RuntimeException('Carrier not found', 404);
        }

        try {
            $this->carrierRepository->remove($carrier);
            $this->carrierRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete carrier: ' . $e->getMessage(), 0, $e);
        }

        return new CarrierResponse($carrier);
    }
}
