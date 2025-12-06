<?php

namespace App\CheckingList\DTO\QualityCheck;

use App\Common\DTO\BaseDto;

class UpdateQualityCheckRequestDto extends BaseDto
{
    public function __construct(
        // Graphic fields
        public ?bool $graphic_relevant = null,
        public ?bool $graphic_validation = null,
        public ?bool $graphic_seen = null,
        public ?string $graphic_comment = null,
        
        // Instruction fields
        public ?bool $instruction_relevant = null,
        public ?bool $instruction_compliance_validation = null,
        public ?bool $instruction_seen = null,
        public ?string $instruction_comment = null,
        
        // Repair fields
        public ?bool $repair_relevant = null,
        public ?bool $repair_relevant_validation = null,
        public ?bool $repair_seen = null,
        public ?string $repair_comment = null,
        
        // Tightness fields
        public ?bool $tightness_relevant = null,
        public ?bool $tightness_validation = null,
        public ?bool $tightness_seen = null,
        public ?string $tightness_comment = null,
        
        // Wool fields
        public ?bool $wool_relevant = null,
        public ?bool $wool_quality_validation = null,
        public ?bool $wool_seen = null,
        public ?string $wool_comment = null,
        
        // Silk fields
        public ?bool $silk_relevant = null,
        public ?bool $silk_quality_validation = null,
        public ?bool $silk_seen = null,
        public ?string $silk_comment = null,
        
        // Special Shape fields
        public ?bool $special_shape_relevant = null,
        public ?bool $special_shape_relevant_validation = null,
        public ?bool $special_shape_seen = null,
        public ?string $special_shape_comment = null,
        
        // Corps Ondu Coins fields
        public ?bool $corps_ondu_coins_relevant = null,
        public ?bool $corps_ondu_coins_validation = null,
        public ?bool $corps_ondu_coins_seen = null,
        public ?string $corps_ondu_coins_comment = null,
        
        // Velour Author fields
        public ?bool $velour_author_relevant = null,
        public ?bool $velour_author_validation = null,
        public ?bool $velour_author_seen = null,
        public ?string $velour_comment = null,
        
        // Washing fields
        public ?bool $washing_relevant = null,
        public ?bool $washing_validation = null,
        public ?bool $washing_seen = null,
        public ?string $waching_comment = null,
        
        // Cleaning fields
        public ?bool $cleaning_relevant = null,
        public ?bool $cleaning_validation = null,
        public ?bool $cleaning_seen = null,
        public ?string $cleaning_comment = null,
        
        // Carving fields
        public ?bool $carving_relevant = null,
        public ?bool $carving_validation = null,
        public ?bool $carving_seen = null,
        public ?string $carving_comment = null,
        
        // Fabric Color fields
        public ?bool $fabric_color_relevant = null,
        public ?bool $fabric_color_validation = null,
        public ?bool $fabric_color_seen = null,
        public ?string $fabric_color_comment = null,
        
        // Frange fields
        public ?bool $frange_relevant = null,
        public ?bool $frange_validation = null,
        public ?bool $frange_seen = null,
        public ?string $frang_comment = null,
        
        // No Binding fields
        public ?bool $no_binding_relevant = null,
        public ?bool $no_binding_validation = null,
        public ?bool $no_binding_seen = null,
        public ?string $no_binding_comment = null,
        
        // Signature fields
        public ?bool $signature_relevant = null,
        public ?bool $signature_validation = null,
        public ?bool $signature_seen = null,
        public ?string $signature_comment = null,
        
        // Without Backing fields
        public ?bool $without_backing_relevant = null,
        public ?bool $without_backing_validation = null,
        public ?bool $without_backing_seen = null,
        public ?string $without_backing_comment = null,
        
        public ?string $comment = null,
    ) {
    }
}
