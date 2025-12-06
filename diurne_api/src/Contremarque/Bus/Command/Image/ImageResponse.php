<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Image;

use DateTimeImmutable;
use DateTimeInterface;
use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\ImageType;

class ImageResponse implements CommandResponse
{
    public function __construct(
        public int $id,
        public string $image_reference,
        public bool $isValidated,
        public ?bool $hasError,
        public ?string $error,
        public ?string $commentaire,
        public DateTimeImmutable $validatedAt,
        public ImageType $imageType,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'image_reference' => $this->image_reference,
            'isValidated' => $this->isValidated,
            'hasError' => $this->hasError,
            'error' => $this->error,
            'commentaire' => $this->commentaire,
            'validatedAt' => $this->validatedAt->format(DateTimeInterface::ATOM),
            'imageType' => $this->imageType->toArray(),
        ];
    }
}
