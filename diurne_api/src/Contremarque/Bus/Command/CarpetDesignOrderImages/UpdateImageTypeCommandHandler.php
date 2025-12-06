<?php

namespace App\Contremarque\Bus\Command\CarpetDesignOrderImages;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\ImageRepository;
use App\Setting\Repository\ImageTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateImageTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly ImageRepository        $imageRepository, private readonly ImageTypeRepository    $imageTypeRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(UpdateImageTypeCommand $command): UpdateImageTypeResponse
    {
        $image = $this->imageRepository->find($command->getImageId());
        if (!$image) {
            throw new InvalidArgumentException(sprintf('Image with id %d not found', $command->getImageId()));
        }

        $imageType = $this->imageTypeRepository->find($command->getImageTypeId());
        if (!$imageType) {
            throw new InvalidArgumentException(sprintf('ImageType with id %d not found', $command->getImageTypeId()));
        }

        $image->setImageType($imageType);
        $this->entityManager->flush();
        return new UpdateImageTypeResponse('Image type updated successfully', $image->getId());

    }
}