<?php

namespace App\Setting\Bus\Command\Manufacturer;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Manufacturer;
use App\Setting\Repository\ManufacturerRepository;

class DeleteManufacturerCommandHandler implements CommandHandler
{
    public function __construct(private readonly ManufacturerRepository $manufacturerRepository) {}

    public function __invoke(DeleteManufacturerCommand $command): ManufacturerResponse
    {
        $manufacturer = $this->manufacturerRepository->find($command->id);
        if (!$manufacturer) {
            throw new RuntimeException('Manufacturer not found', 404);
        }

        try {
            $this->manufacturerRepository->remove($manufacturer);
            $this->manufacturerRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete manufacturer: ' . $e->getMessage(), 0, $e);
        }

        return new ManufacturerResponse($manufacturer);
    }
}
