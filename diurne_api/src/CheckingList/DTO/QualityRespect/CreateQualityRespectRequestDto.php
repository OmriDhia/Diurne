<?php

namespace App\CheckingList\DTO\QualityRespect;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateQualityRespectRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive]
        public readonly int     $checking_list_id,
        public readonly ?bool   $respect_plan_valid = null,
        public readonly ?string $respect_plan_comment = null,
        public readonly ?bool   $respect_door_height_valid = null,
        public readonly ?string $respect_door_height_comment = null,
        public readonly ?bool   $respect_max_min_length_valid = null,
        public readonly ?bool   $respect_max_min_width_valid = null,
        public readonly ?bool   $respectwall_distance_right_valid = null,
        public readonly ?bool   $respectwall_distance_left_valid = null,
        public readonly ?string $penalty = null,
        public readonly ?bool   $respect_foss_valide = null,
        public readonly ?string $respect_foss_comment = null,
        public readonly ?bool   $respect_other_carpet_valid = null,
        public readonly ?string $respect_other_carpet_comment = null,
        public readonly ?bool   $respect_color_valid = null,
        public readonly ?string $respect_color_comment = null,
        public readonly ?bool   $respect_material_valid = null,
        public readonly ?string $respect_material_comment = null,
        public readonly ?bool   $respect_remark_valid = null,
        public readonly ?string $respect_remark_comment = null,
        public readonly ?bool   $respect_velour_valid = null,
        public readonly ?string $respect_velour_comment = null,
        public readonly ?bool   $wall_distance_top_valid = null,
        public readonly ?bool   $wall_distance_bottom_valid = null,
        public readonly ?bool   $order_status = null,
    )
    {
    }
}
