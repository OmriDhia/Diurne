<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateFinishing;

use App\Common\Bus\Command\CommandResponse;

class UpdateFinishingResponse implements CommandResponse
{
    public function __construct(
        public int $id,
        public ?string $fabricColor,
        public ?bool $fringe,
        public ?bool $withoutBanking,
        public ?bool $noBinding,
        public ?bool $mzCarved,
        public ?string $otherCarvedSignature,
        public ?string $standardVelvetHeight,
        public ?string $specialVelvetHeight
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fabricColor' => $this->fabricColor,
            'fringe' => $this->fringe,
            'withoutBanking' => $this->withoutBanking,
            'noBinding' => $this->noBinding,
            'mzCarved' => $this->mzCarved,
            'otherCarvedSignature' => $this->otherCarvedSignature,
            'standardVelvetHeight' => $this->standardVelvetHeight,
            'specialVelvetHeight' => $this->specialVelvetHeight,
        ];
    }
}
