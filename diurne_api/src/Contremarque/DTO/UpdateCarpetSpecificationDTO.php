<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCarpetSpecificationDTO
{
    public function __construct(
        /**
         * @Assert\NotBlank
         *
         * @Assert\Length(max=50)
         */
        public ?string $reference,
        /**
         * @Assert\Length(max=255)
         */
        public ?string $description,
        /**
         * @Assert\NotNull
         *
         * @Assert\GreaterThan(value=0)
         */
        public ?int $collectionId,
        /**
         * @Assert\NotNull
         *
         * @Assert\GreaterThan(value=0)
         */
        public ?int $modelId,
        /**
         * @Assert\NotNull
         *
         * @Assert\GreaterThan(value=0)
         */
        public ?int $qualityId,
        public ?bool $hasSpecialShape,
        public ?bool $isOversized,
        /**
         * @Assert\GreaterThan(value=0)
         */
        public ?int $specialShapeId,
        /**
         * @Assert\NotNull
         *
         * @Assert\Type("array")
         */
        public ?array $dimensions,
        /**
         * @Assert\NotNull
         *
         * @Assert\Type("array")
         */
        public ?array $materials,

        #[Assert\PositiveOrZero(message: 'The random weight must be a positive number or zero.')]
        public readonly ?float $randomWeight,
    ) {}
}
