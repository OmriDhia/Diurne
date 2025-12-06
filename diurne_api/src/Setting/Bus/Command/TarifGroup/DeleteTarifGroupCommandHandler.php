<?php

namespace App\Setting\Bus\Command\TarifGroup;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\TarifGroup;
use App\Setting\Repository\TarifGroupRepository;

class DeleteTarifGroupCommandHandler implements CommandHandler
{
    public function __construct(private readonly TarifGroupRepository $tarifgroupRepository) {}

    public function __invoke(DeleteTarifGroupCommand $command): TarifGroupResponse
    {
        $tarifgroup = $this->tarifgroupRepository->find($command->id);
        if (!$tarifgroup) {
            throw new RuntimeException('TarifGroup not found', 404);
        }

        try {
            $this->tarifgroupRepository->remove($tarifgroup);
            $this->tarifgroupRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete tarifgroup: ' . $e->getMessage(), 0, $e);
        }

        return new TarifGroupResponse($tarifgroup);
    }
}
