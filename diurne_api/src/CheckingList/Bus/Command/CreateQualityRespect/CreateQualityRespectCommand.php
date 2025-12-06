<?php

namespace App\CheckingList\Bus\Command\CreateQualityRespect;

use App\Common\Bus\Command\Command;

class CreateQualityRespectCommand implements Command
{
    public function __construct(
        public readonly int                $checkingListId,
        public readonly ?bool              $respectPlanValid = null,
        public readonly ?string            $respectPlanComment = null,
        public readonly ?bool              $respectDoorHeightValid = null,
        public readonly ?string            $respectDoorHeightComment = null,
        public readonly ?bool              $respectMaxMinLengthValid = null,
        public readonly ?bool              $respectMaxMinWidthValid = null,
        public readonly ?bool              $respectwallDistanceRightValid = null,
        public readonly ?bool              $respectwallDistanceLeftValid = null,
        public readonly ?string $penalty = null,
        public readonly ?bool              $respectFossValide = null,
        public readonly ?string            $respectFossComment = null,
        public readonly ?bool              $respectOtherCarpetValid = null,
        public readonly ?string            $respectOtherCarpetComment = null,
        public readonly ?bool              $respectColorValid = null,
        public readonly ?string            $respectColorComment = null,
        public readonly ?bool              $respectMaterialValid = null,
        public readonly ?string            $respectMaterialComment = null,
        public readonly ?bool              $respectRemarkValid = null,
        public readonly ?string            $respectRemarkComment = null,
        public readonly ?bool              $respectVelourValid = null,
        public readonly ?string            $respectVelourComment = null,
        public readonly ?bool              $wallDistanceTopValid = null,
        public readonly ?bool              $wallDistanceBottomValid = null,
        public readonly ?bool              $orderStatus = null,
    )
    {
    }
}
