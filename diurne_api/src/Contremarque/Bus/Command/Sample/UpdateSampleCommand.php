<?php

namespace App\Contremarque\Bus\Command\Sample;

use App\Common\Bus\Command\Command;
use App\Contremarque\ValueObject\Dimension;

class UpdateSampleCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $locationId,
        public readonly ?int $collectionId,
        public readonly ?int $modelId,
        public readonly ?int $statusId,
        public readonly ?int $qualityId,
        public readonly ?string $diCommandNumber,
        public readonly ?string $rn,
        public readonly ?string $transmissionDate,
        public readonly ?string $customerComment,
        public readonly ?array $imageIds,
        public readonly ?array $attachmentIds,
        public readonly ?Dimension $dimension
    ) {}
}
