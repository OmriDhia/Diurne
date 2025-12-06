<?php

namespace App\CheckingList\Bus\Command\UpdateQualityRespect;

use App\Common\Bus\Command\Command;

class UpdateQualityRespectCommand implements Command
{
    public function __construct(
        public readonly int                $id,
        
        // Respect Plan
        public readonly ?bool              $respectPlanRelevant = null,
        public readonly ?bool              $respectPlanValid = null,
        public readonly ?bool              $respectPlanSeen = null,
        public readonly ?string            $respectPlanComment = null,
        
        // Respect Door Height
        public readonly ?bool              $respectDoorHeightRelevant = null,
        public readonly ?bool              $respectDoorHeightValid = null,
        public readonly ?bool              $respectDoorHeightSeen = null,
        public readonly ?string            $respectDoorHeightComment = null,
        
        // Respect Max Min Length
        public readonly ?bool              $respectMaxMinLengthRelevant = null,
        public readonly ?bool              $respectMaxMinLengthValid = null,
        public readonly ?bool              $respectMaxMinLengthSeen = null,
        
        // Respect Max Min Width
        public readonly ?bool              $respectMaxMinWidthRelevant = null,
        public readonly ?bool              $respectMaxMinWidthValid = null,
        public readonly ?bool              $respectMaxMinWidthSeen = null,
        
        // Wall Distance Right
        public readonly ?bool              $respectwallDistanceRightRelevant = null,
        public readonly ?bool              $respectwallDistanceRightValid = null,
        public readonly ?bool              $respectwallDistanceRightSeen = null,
        
        // Wall Distance Left
        public readonly ?bool              $respectwallDistanceLeftRelevant = null,
        public readonly ?bool              $respectwallDistanceLeftValid = null,
        public readonly ?bool              $respectwallDistanceLeftSeen = null,
        
        // Respect Foss
        public readonly ?bool              $respectFossRelevant = null,
        public readonly ?bool              $respectFossValide = null,
        public readonly ?bool              $respectFossSeen = null,
        public readonly ?string            $respectFossComment = null,
        
        // Respect Other Carpet
        public readonly ?bool              $respectOtherCarpetRelevant = null,
        public readonly ?bool              $respectOtherCarpetValid = null,
        public readonly ?bool              $respectOtherCarpetSeen = null,
        public readonly ?string            $respectOtherCarpetComment = null,
        
        // Respect Color
        public readonly ?bool              $respectColorRelevant = null,
        public readonly ?bool              $respectColorValid = null,
        public readonly ?bool              $respectColorSeen = null,
        public readonly ?string            $respectColorComment = null,
        
        // Respect Material
        public readonly ?bool              $respectMaterialRelevant = null,
        public readonly ?bool              $respectMaterialValid = null,
        public readonly ?bool              $respectMaterialSeen = null,
        public readonly ?string            $respectMaterialComment = null,
        
        // Respect Remark
        public readonly ?bool              $respectRemarkRelevant = null,
        public readonly ?bool              $respectRemarkValid = null,
        public readonly ?bool              $respectRemarkSeen = null,
        public readonly ?string            $respectRemarkComment = null,
        
        // Respect Velour
        public readonly ?bool              $respectVelourRelevant = null,
        public readonly ?bool              $respectVelourValid = null,
        public readonly ?bool              $respectVelourSeen = null,
        public readonly ?string            $respectVelourComment = null,
        
        // Wall Distance Top
        public readonly ?bool              $wallDistanceTopRelevant = null,
        public readonly ?bool              $wallDistanceTopValid = null,
        public readonly ?bool              $wallDistanceTopSeen = null,
        
        // Wall Distance Bottom
        public readonly ?bool              $wallDistanceBottomRelevant = null,
        public readonly ?bool              $wallDistanceBottomValid = null,
        public readonly ?bool              $wallDistanceBottomSeen = null,
        
        // Other fields
        public readonly ?string $penalty = null,
        public readonly ?bool              $orderStatus = null,
    )
    {
    }
}
