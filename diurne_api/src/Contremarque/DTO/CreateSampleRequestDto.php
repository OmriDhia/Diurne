<?php

namespace App\Contremarque\DTO;

use App\Contremarque\ValueObject\Dimension;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Constraints as Assert;

#[MapRequestPayload]
class CreateSampleRequestDto
{
    public function __construct(#[Assert\PositiveOrZero(message: 'Carpet Design Order ID must be a positive integer or zero')]
    public readonly ?int $carpetDesignOrderId, #[Assert\NotBlank(message: 'Location ID cannot be empty')]
    #[Assert\Positive(message: 'Location ID must be a positive integer')]
    public readonly ?int $locationId, #[Assert\PositiveOrZero(message: 'Collection ID must be a positive integer or zero')]
    public readonly ?int $collectionId, #[Assert\PositiveOrZero(message: 'Model ID must be a positive integer or zero')]
    public readonly ?int $modelId, #[Assert\NotBlank(message: 'Status ID cannot be empty')]
    #[Assert\Positive(message: 'Status ID must be a positive integer')]
    public readonly ?int $statusId, #[Assert\NotBlank(message: 'Quality ID cannot be empty')]
    #[Assert\Positive(message: 'Quality ID must be a positive integer')]
    public readonly ?int $qualityId, #[Assert\NotBlank(message: 'DI Command Number cannot be empty')]
    #[Assert\Length(max: 50, maxMessage: 'DI Command Number cannot exceed {{ limit }} characters')]
    public readonly ?string $diCommandNumber, #[Assert\Length(max: 50, maxMessage: 'RN cannot exceed {{ limit }} characters')]
    public readonly ?string $rn, #[Assert\DateTime(message: 'Transmission Date must be a valid datetime')]
    public readonly ?string $transmissionDate, public readonly ?string $customerComment, #[Assert\All([
        new Assert\Positive(message: 'Image IDs must be positive integers'),
    ])]
    public readonly ?array $imageIds, #[Assert\All([
        new Assert\Positive(message: 'Attachment IDs must be positive integers'),
    ])]
    public readonly ?array $attachmentIds, #[Assert\NotNull(message: 'Dimension cannot be null')]
    #[Assert\Valid]
    public readonly Dimension $dimension)
    {
    }
}
