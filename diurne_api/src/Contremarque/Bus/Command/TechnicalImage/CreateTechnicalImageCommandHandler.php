<?php

namespace App\Contremarque\Bus\Command\TechnicalImage;

use InvalidArgumentException;
use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\ImageCommand\TechnicalImage;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Setting\Repository\ImageTypeRepository;

class CreateTechnicalImageCommandHandler implements CommandHandler
{
    public function __construct(private readonly ImageCommandRepository $imageCommandRepository, private readonly ImageTypeRepository $imageTypeRepository, private readonly AttachmentRepository $attachmentRepository)
    {
    }

    public function __invoke(CreateTechnicalImageCommand $command): TechnicalImageResponse
    {
        $imageCommand = $this->imageCommandRepository->find($command->getImageCommandId());
        if (!$imageCommand) {
            throw new InvalidArgumentException('Image command not found');
        }
        $imageType = $this->imageTypeRepository->find($command->getImageTypeId());
        if (!$imageType) {
            throw new InvalidArgumentException('Image type not found');
        }
        $attachment = $this->attachmentRepository->find($command->getAttachmentId());
        if (!$attachment) {
            throw new InvalidArgumentException('Attachment not found');
        }
        $technicalImage = new TechnicalImage();
        $technicalImage->setImageCommand($imageCommand);
        $technicalImage->setImageType($imageType);
        $technicalImage->setName($command->getName());
        $technicalImage->setAttachment($attachment);
        $technicalImage->setCreatedAt(new DateTimeImmutable());
        $technicalImage->setUpdatedAt(new DateTimeImmutable());
        $this->imageCommandRepository->persist($technicalImage);
        $this->imageCommandRepository->flush();

        return new TechnicalImageResponse($technicalImage);
    }
}
