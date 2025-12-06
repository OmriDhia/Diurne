<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Data Transfer Object for Carpet Specification.
 *
 * @property-read string $reference
 * @property-read string $description
 * @property-read int $collectionId
 * @property-read int $modelId
 * @property-read int $qualityId
 * @property-read ?bool $hasSpecialShape
 * @property-read ?bool $isOversized
 * @property-read ?int $specialShapeId
 * @property-read ?float $randomWeight
 * @property-read ?array $dimensions
 * @property-read array $materials
 */
final class CarpetSpecificationDTO extends BaseDto
{
    #[Assert\All([
        new Assert\NotBlank(message: 'Each dimension set cannot be empty.'),
        new Assert\All([
            new Assert\Collection(
                fields: [
                    'dimension_id' => [
                        new Assert\NotBlank(message: 'The dimension ID cannot be blank.'),
                        new Assert\Type(type: 'integer', message: 'The dimension ID must be an integer.'),
                        new Assert\GreaterThan(0, message: 'The dimension ID must be greater than 0.'),
                    ],
                    'value' => [
                        new Assert\NotBlank(message: 'The dimension value cannot be blank.'),
                        new Assert\Regex(
                            pattern: '/^(0|[1-9]\d*(\.\d+)?)$/',
                            message: 'The dimension value must be a positive number or zero (string or numeric).'
                        ),
                    ],
                ],
                allowMissingFields: false,
                allowExtraFields: false
            ),
        ]),
    ])]
    public readonly ?array $dimensions;

    public readonly ?int $specialShapeId;

    public function __construct(
        #[Assert\Length(max: 50, maxMessage: 'The reference cannot be longer than {{ limit }} characters.')]
        public readonly string $reference,

        #[Assert\NotBlank(message: 'The description cannot be blank.')]
        #[Assert\Length(max: 255, maxMessage: 'The description cannot be longer than {{ limit }} characters.')]
        public readonly string $description,

        #[Assert\NotNull(message: 'The collection ID cannot be null.')]
        #[Assert\GreaterThan(0, message: 'The collection ID must be greater than 0.')]
        public readonly int    $collectionId,

        #[Assert\NotNull(message: 'The model ID cannot be null.')]
        #[Assert\GreaterThan(0, message: 'The model ID must be greater than 0.')]
        public readonly int    $modelId,

        #[Assert\NotNull(message: 'The quality ID cannot be null.')]
        #[Assert\GreaterThan(0, message: 'The quality ID must be greater than 0.')]
        public readonly int    $qualityId,

        public ?bool           $hasSpecialShape,

        public readonly ?bool  $isOversized,

        ?int                   $specialShapeId,

        #[Assert\PositiveOrZero(message: 'The random weight must be a positive number or zero.')]
        public readonly ?float $randomWeight,

        ?array                 $dimensions,

        #[Assert\NotNull(message: 'The materials cannot be null.')]
        #[Assert\All([
            new Assert\NotBlank(message: 'Each material entry cannot be empty.'),
            new Assert\Collection(
                fields: [
                    'material_id' => [
                        new Assert\NotBlank(message: 'The material ID cannot be blank.'),
                        new Assert\Type(type: 'integer', message: 'The material ID must be an integer.'),
                        new Assert\GreaterThan(0, message: 'The material ID must be greater than 0.'),
                    ],
                    'rate' => [
                        new Assert\NotBlank(message: 'The rate cannot be blank.'),
                        new Assert\Regex(
                            pattern: '/^(100|[1-9]?[0-9])$/',
                            message: 'The rate must be a number between 0 and 100 (e.g., "50").'
                        ),
                    ],
                ],
                allowMissingFields: false,
                allowExtraFields: false
            ),
        ])]
        public readonly array  $materials
    )
    {
        // Normalize dimensions: convert object with keys "1", "2", etc. to an array and ensure value is a string
        $this->dimensions = $dimensions !== null ? array_map(function ($dimensionSet) {
            return array_map(function ($dimension) {
                return [
                    'dimension_id' => (int)$dimension['dimension_id'],
                    'value' => (string)$dimension['value']
                ];
            }, $dimensionSet);
        }, $dimensions) : null;

        // Normalize specialShapeId: set to null if hasSpecialShape is false
        $this->specialShapeId = $hasSpecialShape === true ? $specialShapeId : null;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        $this->validateSpecialShapeId($context);
        $this->validateDimensions($context);
        $this->validateMaterials($context);
    }

    private function validateSpecialShapeId(ExecutionContextInterface $context): void
    {
        if (!$this->hasSpecialShape) {
            return; // Special shape is optional
        }

        if ($this->specialShapeId === null) {
            $context->buildViolation('The special shape ID is required when hasSpecialShape is true.')
                ->atPath('specialShapeId')
                ->addViolation();
            return;
        }

        if ($this->specialShapeId <= 0) {
            $context->buildViolation('The special shape ID must be greater than 0.')
                ->atPath('specialShapeId')
                ->addViolation();
        }
    }

    private function validateDimensions(ExecutionContextInterface $context): void
    {
        if ($this->dimensions === null || empty($this->dimensions)) {
            return; // Dimensions are optional
        }

        // Check if all values are zero
        $allValuesAreZero = true;
        foreach ($this->dimensions as $index => $dimensionSet) {
            foreach ($dimensionSet as $dimension) {
                $value = $dimension['value'] ?? null;
                if ($value === null) {
                    continue; // Skip if value is not set (will be caught by other validation)
                }
                if ($value !== '0' && (float)$value !== 0.0) {
                    $allValuesAreZero = false;
                    break 2; // Break out of both loops
                }
            }
        }

        if ($allValuesAreZero) {
            $context->buildViolation('At least one dimension value must be greater than 0.')
                ->atPath('dimensions')
                ->addViolation();
        }
    }

    private function validateMaterials(ExecutionContextInterface $context): void
    {
        if (empty($this->materials)) {
            return; // Materials are already validated as not null
        }

        // Validate that the sum of rates equals 100
        $totalRate = 0;
        foreach ($this->materials as $index => $material) {
            $rateValue = $material['rate'];
            // Convert the rate string to float (no need to remove % anymore)
            $rate = (float)$rateValue;
            $totalRate += $rate;
        }

        if (abs($totalRate - 100.0) > 0.01) { // Allow for small floating-point errors
            $context->buildViolation('The sum of material rates must equal 100.')
                ->atPath('materials')
                ->addViolation();
        }
    }
}
