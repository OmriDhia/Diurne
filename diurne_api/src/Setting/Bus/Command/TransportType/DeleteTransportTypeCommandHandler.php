<?php

namespace App\Setting\Bus\Command\TransportType;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\TransportType;
use App\Setting\Repository\TransportTypeRepository;

class DeleteTransportTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly TransportTypeRepository $transporttypeRepository) {}

    public function __invoke(DeleteTransportTypeCommand $command): TransportTypeResponse
    {
        $transporttype = $this->transporttypeRepository->find($command->id);
        if (!$transporttype) {
            throw new RuntimeException('TransportType not found', 404);
        }

        try {
            $this->transporttypeRepository->remove($transporttype);
            $this->transporttypeRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete transporttype: ' . $e->getMessage(), 0, $e);
        }

        return new TransportTypeResponse($transporttype);
    }
}
