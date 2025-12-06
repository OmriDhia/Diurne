<?php

namespace App\Setting\Bus\Command\ImageType;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Entity\ImageCategoryEnum;
use App\Setting\Repository\ImageTypeRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateImageTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly ImageTypeRepository $imageTypeRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateImageTypeCommand $command): UpdateImageTypeResponse
    {
        $imageType = $this->imageTypeRepository->find($command->getId());

        if (null === $imageType) {
            throw new ResourceNotFoundException();
        }
        if (null !== $command->getName()) {
            $imageType->setName($command->getName());
        }

        if (null !== $command->getDescription()) {
            $imageType->setDescription($command->getDescription());
        }
        if (null !== $command->getCategory()) {
            $category = ImageCategoryEnum::class::from($command->getCategory());
            $imageType->setCategory($category);
        }

        $this->imageTypeRepository->persist($imageType);
        $this->imageTypeRepository->flush();

        return new UpdateImageTypeResponse($imageType);
    }
}
