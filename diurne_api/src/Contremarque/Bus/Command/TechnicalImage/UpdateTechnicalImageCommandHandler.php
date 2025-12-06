<?php

namespace App\Contremarque\Bus\Command\TechnicalImage;

use InvalidArgumentException;
use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Contremarque\Repository\TechnicalImageRepository;
use App\Setting\Repository\ImageTypeRepository;

class UpdateTechnicalImageCommandHandler implements CommandHandler
{
    public function __construct(private readonly TechnicalImageRepository $technicalCommandRepository, private readonly ImageTypeRepository $imageTypeRepository, private readonly AttachmentRepository $attachmentRepository, private readonly ImageCommandRepository $imageCommandRepository)
    {
    }

    public function __invoke(UpdateTechnicalImageCommand $command): ?TechnicalImageResponse
    {
        $technicalImage = $this->technicalCommandRepository->find($command->getId());

        if (!$technicalImage) {
            throw new InvalidArgumentException('Technical image not found');
        }

        if (!empty($command->getName())) {
            $technicalImage->setName($command->getName());
        }
        if (!empty($command->getImageCommandId())) {
            $imageType = $this->imageTypeRepository->find($command->getImageTypeId());
            if (!$imageType) {
                throw new InvalidArgumentException('Image type not found');
            }
            $technicalImage->setImageType($imageType);
        }
        if (!empty($command->getImageCommandId())) {
            $imageCommand = $this->imageCommandRepository->find($command->getImageCommandId());
            if (!$imageCommand) {
                throw new InvalidArgumentException('Image command not found');
            }
            $technicalImage->setImageCommand($imageCommand);
        }

        if (!empty($command->getAttachmentId())) {
            $attachment = $this->attachmentRepository->find($command->getAttachmentId());
            if (!$attachment) {
                throw new InvalidArgumentException('Attachment not found');
            }
            $technicalImage->setAttachment($attachment);
        }
        $technicalImage->setUpdatedAt(new DateTimeImmutable());
        $this->technicalCommandRepository->persist($technicalImage);
        $this->technicalCommandRepository->flush();

        return new TechnicalImageResponse($technicalImage);
    }
}
