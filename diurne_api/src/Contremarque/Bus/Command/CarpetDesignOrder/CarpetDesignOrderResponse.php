<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetDesignOrder;

use App\Contremarque\Entity\CarpetDesignOrder;

class CarpetDesignOrderResponse
{
    private readonly int $id;
    private readonly int $projectDiId;
    private readonly ?int $locationId;
    private readonly ?array $designerAssignments;
    private readonly ?int $statusId;
    private readonly ?string $modelName;
    private readonly ?string $variation;
    private readonly ?bool $jpeg;
    private readonly ?bool $impression;
    private readonly ?bool $impressionBarreDeLaine;

    public function __construct(CarpetDesignOrder $carpetDesignOrder, private readonly ?array $errors = [])
    {
        $this->id = $carpetDesignOrder->getId();
        $this->projectDiId = $carpetDesignOrder->getProjectDi() ? $carpetDesignOrder->getProjectDi()->getId() : 0;
        $this->locationId = $carpetDesignOrder->getLocation() ? $carpetDesignOrder->getLocation()->getId() : null;
        $this->designerAssignments = $carpetDesignOrder->getDesigners()->map(fn($assignment) => $assignment->getId())->toArray();
        $this->statusId = $carpetDesignOrder->getStatus() ? $carpetDesignOrder->getStatus()->getId() : null;
        $this->modelName = $carpetDesignOrder->getModelName();
        $this->variation = $carpetDesignOrder->getVariation();
        $this->jpeg = $carpetDesignOrder->isJpeg();
        $this->impression = $carpetDesignOrder->isImpression();
        $this->impressionBarreDeLaine = $carpetDesignOrder->isImpressionBarreDeLaine();

    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'project_di_id' => $this->projectDiId,
            'location_id' => $this->locationId,
            'designer_assignments' => $this->designerAssignments,
            'status_id' => $this->statusId,
            'model_name' => $this->modelName,
            'variation' => $this->variation,
            'jpeg' => $this->jpeg,
            'impression' => $this->impression,
            'impression_barre_de_laine' => $this->impressionBarreDeLaine,
            'errors' => $this->errors,
        ];
    }
}
