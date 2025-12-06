<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateDesignerComposition;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\DesignerComposition;

final class DesignerCompositionResponse implements CommandResponse
{
    public function __construct(
        public DesignerComposition $designerComposition
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->designerComposition->getId(),
            'material_id' => $this->designerComposition->getMaterial()?->getId(),
            'carpetSpecification_id' => $this->designerComposition->getCarpetSpecification()?->getId(),
            'rate' => $this->designerComposition->getRate(),
        ];
    }
}
