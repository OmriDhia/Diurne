<?php

namespace App\Setting\Bus\Command\ImageType;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\ImageCategoryEnum;
use App\Setting\Entity\ImageType;
use App\Setting\Repository\ImageTypeRepository;

class CreateImageTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly ImageTypeRepository $imageTypeRepository)
    {
    }

    public function __invoke(CreateImageTypeCommand $command): ImageTypeResponse
    {
        $imageType = new ImageType();
        $imageType->setName($command->getName());
        $imageType->setDescription($command->getDescription());
        $cartegory = ImageCategoryEnum::class::from($command->getCategory());
        $imageType->setCategory($cartegory);
        $this->imageTypeRepository->persist($imageType);
        $this->imageTypeRepository->flush();

        return new ImageTypeResponse($imageType);
    }
}
