<?php

namespace App\Setting\Bus\Command\Model;

use Exception;
use RuntimeException;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Setting\Entity\Model;
use App\Setting\Repository\ModelRepository;

class CreateModelCommandHandler implements CommandHandler
{
    public function __construct(private readonly ModelRepository $modelRepository) {}

    public function __invoke(CreateModelCommand $command): ModelResponse
    {
        $model = $this->modelRepository->findOneBy(['code' => $command->code]);
        if ($model) {
            throw new DuplicateValidationResourceException('Model already exists');
        }
        $model = new Model();
        $model->setCode($command->code);
        $model->setNumberMax($command->numberMax);

        try {
            $this->modelRepository->persist($model);
            $this->modelRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to create model: ' . $e->getMessage(), 0, $e);
        }

        return new ModelResponse($model);
    }
}
