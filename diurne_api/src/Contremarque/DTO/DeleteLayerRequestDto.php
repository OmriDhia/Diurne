<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;

class DeleteLayerRequestDto
{
    public function __construct(
        /**
         * @var int[]
         * @SerializedName("layerIds")
         */
        public array $layerIds
    )
    {
    }
}
