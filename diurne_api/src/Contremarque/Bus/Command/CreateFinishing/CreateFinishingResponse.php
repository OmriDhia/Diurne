<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateFinishing;

use App\Common\Bus\Command\CommandResponse;

class CreateFinishingResponse implements CommandResponse
{
    public function __construct(private readonly string $id, private readonly string $fabricColor, private readonly bool $fringe, private readonly bool $withoutBanking, private readonly bool $noBinding, private readonly bool $mzCarved, private readonly ?string $otherCarvedSignature, private readonly string $standardVelvetHeight, private readonly string $specialVelvetHeight)
    {
    }

    /**
     * Convert the response to an array format.
     */
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
