<?php

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use App\Contremarque\ValueObject\Dimension;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Constraints as Assert;

#[MapRequestPayload]
class UpdateSampleRequestDto extends BaseDto
{
    public function __construct(#[Assert\PositiveOrZero(message: 'Carpet Design Order ID must be a positive integer or zero')]
    public readonly ?int $locationId = null, #[Assert\PositiveOrZero(message: 'Collection ID must be a positive integer or zero')]
    public readonly ?int $collectionId = null, #[Assert\PositiveOrZero(message: 'Model ID must be a positive integer or zero')]
    public readonly ?int $modelId = null, #[Assert\Positive(message: 'Status ID must be a positive integer')]
    public readonly ?int $statusId = null, #[Assert\Positive(message: 'Quality ID must be a positive integer')]
    public readonly ?int $qualityId = null, #[Assert\Length(max: 50, maxMessage: 'DI Command Number cannot exceed {{ limit }} characters')]
    public readonly ?string $diCommandNumber = null, #[Assert\Length(max: 50, maxMessage: 'RN cannot exceed {{ limit }} characters')]
    public readonly ?string $rn = null, #[Assert\DateTime(message: 'Transmission Date must be a valid datetime')]
    public readonly ?string $transmissionDate = null, public readonly ?string $customerComment = null, #[Assert\All([
        new Assert\Positive(message: 'Image IDs must be positive integers'),
    ])]
    public readonly ?array $imageIds = null, #[Assert\All([
        new Assert\Positive(message: 'Attachment IDs must be positive integers'),
    ])]
    public readonly ?array $attachmentIds = null, #[Assert\Valid]
    public readonly ?Dimension $dimension = null) {}
}
