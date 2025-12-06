<?php

namespace App\Setting\Bus\Command\Model;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Model;

class ModelResponse implements CommandResponse
{
    public function __construct(private readonly Model $model)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->model->getId(),
            'code' => $this->model->getCode(),
            'number_max' => $this->model->getNumberMax(),
        ];
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
