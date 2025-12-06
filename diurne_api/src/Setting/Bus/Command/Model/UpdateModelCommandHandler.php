<?php

namespace App\Setting\Bus\Command\Model;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Model;
use App\Setting\Repository\ModelRepository;

class UpdateModelCommandHandler implements CommandHandler
{
    public function __construct(private readonly ModelRepository $modelRepository) {}

    public function __invoke(UpdateModelCommand $command): ModelResponse
    {
        $model = $this->modelRepository->find($command->id);
        if (!$model) {
            throw new RuntimeException('Model not found', 404);
        }
        if ($command->code) $model->setCode($command->code);
        if ($command->numberMax) $model->setNumberMax($command->numberMax);

        try {
            $this->modelRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to update model: ' . $e->getMessage(), 0, $e);
        }

        return new ModelResponse($model);
    }
}
