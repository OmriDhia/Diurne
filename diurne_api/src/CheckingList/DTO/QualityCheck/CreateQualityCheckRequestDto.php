<?php

namespace App\CheckingList\DTO\QualityCheck;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateQualityCheckRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive]
        public readonly int $checking_list_id,
        public readonly ?bool $graphic_validation = null,
        public readonly ?string $graphic_comment = null,
        public readonly ?bool $instruction_compliance_validation = null,
        public readonly ?string $instruction_comment = null,
        public readonly ?bool $repair_relevant_validation = null,
        public readonly ?string $repair_comment = null,
        public readonly ?bool $tightness_validation = null,
        public readonly ?string $tightness_comment = null,
        public readonly ?bool $wool_quality_validation = null,
        public readonly ?string $wool_comment = null,
        public readonly ?bool $silk_quality_validation = null,
        public readonly ?string $silk_comment = null,
        public readonly ?bool $special_shape_relevant_validation = null,
        public readonly ?string $special_shape_comment = null,
        public readonly ?bool $corps_ondu_coins_validation = null,
        public readonly ?string $corps_ondu_coins_comment = null,
        public readonly ?bool $velour_author_validation = null,
        public readonly ?string $velour_comment = null,
        public readonly ?bool $washing_validation = null,
        public readonly ?string $waching_comment = null,
        public readonly ?bool $cleaning_validation = null,
        public readonly ?string $cleaning_comment = null,
        public readonly ?bool $carving_validation = null,
        public readonly ?string $carving_comment = null,
        public readonly ?bool $fabric_color_validation = null,
        public readonly ?string $fabric_color_comment = null,
        public readonly ?bool $frange_validation = null,
        public readonly ?string $frang_comment = null,
        public readonly ?bool $no_binding_validation = null,
        public readonly ?string $no_binding_comment = null,
        public readonly ?bool $signature_validation = null,
        public readonly ?string $signature_comment = null,
        public readonly ?bool $without_backing_validation = null,
        public readonly ?string $without_backing_comment = null,
        public readonly ?string $comment = null,
    ) {
    }
}
