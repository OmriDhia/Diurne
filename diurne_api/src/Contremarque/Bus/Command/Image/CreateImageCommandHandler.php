<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Image;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\Image;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\ImageRepository;
use App\Setting\Repository\ImageTypeRepository;

class CreateImageCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ImageRepository $imageRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly ImageTypeRepository $imageTypeRepository
    ) {
    }

    public function __invoke(CreateImageCommand $command): ImageResponse
    {
        $existingImage = $this->imageRepository->findOneBy(['image_reference' => $command->getImageReference()]);

        if ($existingImage instanceof Image) {
            throw new DuplicateValidationResourceException();
        }
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find((int) $command->getCarpetDesignOrderId());
        if (!$carpetDesignOrder) {
            throw new ResourceNotFoundException();
        }
        $imageType = $this->imageTypeRepository->find((int) $command->getImageTypeId());
        if (!$imageType) {
            throw new ResourceNotFoundException();
        }
        $image = new Image();
        $image->setImageReference($command->getImageReference())
            ->setValidated($command->isValidated())
            ->setCarpetDesignOrder($carpetDesignOrder)
            ->setHasError($command->hasError())
            ->setError($command->getError())
            ->setImageType($imageType)
            ->setCommentaire($command->getCommentaire())
            ->setValidatedAt($command->getValidatedAt());

        $this->imageRepository->persist($image);
        $this->imageRepository->flush();

        return new ImageResponse(
            $image->getId(),
            $image->getImageReference(),
            $image->isValidated(),
            $image->hasError(),
            $image->getError(),
            $image->getCommentaire(),
            $image->getValidatedAt(),
            $imageType
        );
    }
}
