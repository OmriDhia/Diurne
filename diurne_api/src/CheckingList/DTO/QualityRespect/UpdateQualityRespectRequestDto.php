<?php

namespace App\CheckingList\DTO\QualityRespect;

use App\Common\DTO\BaseDto;

class UpdateQualityRespectRequestDto extends BaseDto
{
    public function __construct(
        // Respect Plan
        public ?bool   $respect_plan_relevant = null,
        public ?bool   $respect_plan_valid = null,
        public ?bool   $respect_plan_seen = null,
        public ?string $respect_plan_comment = null,
        
        // Respect Door Height
        public ?bool   $respect_door_height_relevant = null,
        public ?bool   $respect_door_height_valid = null,
        public ?bool   $respect_door_height_seen = null,
        public ?string $respect_door_height_comment = null,
        
        // Respect Max Min Length
        public ?bool   $respect_max_min_length_relevant = null,
        public ?bool   $respect_max_min_length_valid = null,
        public ?bool   $respect_max_min_length_seen = null,
        
        // Respect Max Min Width
        public ?bool   $respect_max_min_width_relevant = null,
        public ?bool   $respect_max_min_width_valid = null,
        public ?bool   $respect_max_min_width_seen = null,
        
        // Wall Distance Right
        public ?bool   $respectwall_distance_right_relevant = null,
        public ?bool   $respectwall_distance_right_valid = null,
        public ?bool   $respectwall_distance_right_seen = null,
        
        // Wall Distance Left
        public ?bool   $respectwall_distance_left_relevant = null,
        public ?bool   $respectwall_distance_left_valid = null,
        public ?bool   $respectwall_distance_left_seen = null,
        
        // Respect Foss
        public ?bool   $respect_foss_relevant = null,
        public ?bool   $respect_foss_valide = null,
        public ?bool   $respect_foss_seen = null,
        public ?string $respect_foss_comment = null,
        
        // Respect Other Carpet
        public ?bool   $respect_other_carpet_relevant = null,
        public ?bool   $respect_other_carpet_valid = null,
        public ?bool   $respect_other_carpet_seen = null,
        public ?string $respect_other_carpet_comment = null,
        
        // Respect Color
        public ?bool   $respect_color_relevant = null,
        public ?bool   $respect_color_valid = null,
        public ?bool   $respect_color_seen = null,
        public ?string $respect_color_comment = null,
        
        // Respect Material
        public ?bool   $respect_material_relevant = null,
        public ?bool   $respect_material_valid = null,
        public ?bool   $respect_material_seen = null,
        public ?string $respect_material_comment = null,
        
        // Respect Remark
        public ?bool   $respect_remark_relevant = null,
        public ?bool   $respect_remark_valid = null,
        public ?bool   $respect_remark_seen = null,
        public ?string $respect_remark_comment = null,
        
        // Respect Velour
        public ?bool   $respect_velour_relevant = null,
        public ?bool   $respect_velour_valid = null,
        public ?bool   $respect_velour_seen = null,
        public ?string $respect_velour_comment = null,
        
        // Wall Distance Top
        public ?bool   $wall_distance_top_relevant = null,
        public ?bool   $wall_distance_top_valid = null,
        public ?bool   $wall_distance_top_seen = null,
        
        // Wall Distance Bottom
        public ?bool   $wall_distance_bottom_relevant = null,
        public ?bool   $wall_distance_bottom_valid = null,
        public ?bool   $wall_distance_bottom_seen = null,
        
        // Other fields
        public ?string $penalty = null,
        public ?bool   $order_status = null,
    )
    {
    }
}
