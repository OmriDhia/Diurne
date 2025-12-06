<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Image;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\Image;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\ImageRepository;
use App\Setting\Repository\ImageTypeRepository;

class UpdateImageCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ImageRepository $imageRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly ImageTypeRepository $imageTypeRepository
    ) {
    }

    public function __invoke(UpdateImageCommand $command): ImageResponse
    {
        $image = $this->imageRepository->find((int) $command->getId());

        if (!$image instanceof Image) {
            throw new ResourceNotFoundException('Image not found.');
        }

        if (!is_null($command->getImageReference())) {
            $image->setImageReference($command->getImageReference());
        }
        if (!empty($command->getCarpetDesignOrderId())) {
            $carpetDesignOrder = $this->carpetDesignOrderRepository->find((int) $command->getCarpetDesignOrderId());
            if (!$carpetDesignOrder) {
                throw new ResourceNotFoundException();
            }
            $image->setCarpetDesignOrder($carpetDesignOrder);
        }
        if (!is_null($command->isValidated())) {
            $image->setValidated($command->isValidated());
        }

        if (!is_null($command->hasError())) {
            $image->setHasError($command->hasError());
        }

        if (!is_null($command->getError())) {
            $image->setError($command->getError());
        }

        if (!is_null($command->getCommentaire())) {
            $image->setCommentaire($command->getCommentaire());
        }

        if (!is_null($command->getValidatedAt())) {
            $image->setValidatedAt($command->getValidatedAt());
        }

        if (!is_null($command->getImageTypeId())) {
            $imageType = $this->imageTypeRepository->find((int) $command->getImageTypeId());
            $image->setImageType($imageType);
        }

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
            $image->getImageType()
        );
    }
}
