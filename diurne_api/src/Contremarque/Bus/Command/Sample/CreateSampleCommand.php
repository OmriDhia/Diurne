<?php

namespace App\Contremarque\Bus\Command\Sample;

use App\Common\Bus\Command\Command;
use App\Contremarque\ValueObject\Dimension;
use Symfony\Component\Validator\Constraints as Assert;

class CreateSampleCommand implements Command
{
    public function __construct(
        #[Assert\PositiveOrZero(message: 'Carpet Design Order ID must be a positive integer or zero')]
        public readonly ?int $carpetDesignOrderId,

        #[Assert\NotBlank(message: 'Location ID cannot be empty')]
        #[Assert\Positive(message: 'Location ID must be a positive integer')]
        public readonly ?int $locationId,

        #[Assert\PositiveOrZero(message: 'Collection ID must be a positive integer or zero')]
        public readonly ?int $collectionId,

        #[Assert\PositiveOrZero(message: 'Model ID must be a positive integer or zero')]
        public readonly ?int $modelId,

        #[Assert\NotBlank(message: 'Quality ID cannot be empty')]
        #[Assert\Positive(message: 'Quality ID must be a positive integer')]
        public readonly ?int $qualityId,

        #[Assert\NotBlank(message: 'DI Command Number cannot be empty')]
        #[Assert\Length(max: 50, maxMessage: 'DI Command Number cannot exceed {{ limit }} characters')]
        public readonly ?string $diCommandNumber,

        #[Assert\Length(max: 50, maxMessage: 'RN cannot exceed {{ limit }} characters')]
        public readonly ?string $rn,

        #[Assert\DateTime(message: 'Transmission Date must be a valid datetime')]
        public readonly ?string $transmissionDate,

        public readonly ?string $customerComment,

        #[Assert\All([
            new Assert\Positive(message: 'Image IDs must be positive integers'),
        ])]
        public readonly ?array $imageIds,

        #[Assert\All([
            new Assert\Positive(message: 'Attachment IDs must be positive integers'),
        ])]
        public readonly ?array $attachmentIds,

        #[Assert\NotNull(message: 'Dimension cannot be null')]
        public readonly Dimension $dimension,
    ) {}

    public function getCarpetDesignOrderId(): ?int
    {
        return $this->carpetDesignOrderId;
    }

    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    public function getCollectionId(): ?int
    {
        return $this->collectionId;
    }

    public function getModelId(): ?int
    {
        return $this->modelId;
    }

    public function getQualityId(): ?int
    {
        return $this->qualityId;
    }

    public function getDiCommandNumber(): ?string
    {
        return $this->diCommandNumber;
    }

    public function getRn(): ?string
    {
        return $this->rn;
    }

    public function getTransmissionDate(): ?string
    {
        return $this->transmissionDate;
    }

    public function getCustomerComment(): ?string
    {
        return $this->customerComment;
    }

    public function getImageIds(): ?array
    {
        return $this->imageIds;
    }

    public function getAttachmentIds(): ?array
    {
        return $this->attachmentIds;
    }

    public function getDimension(): Dimension
    {
        return $this->dimension;
    }
}
