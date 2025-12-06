<?php

namespace App\Contremarque\Bus\Command\TechnicalImage;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\TechnicalImageRepository;

class DeleteTechnicalImageCommandHandler implements CommandHandler
{
    public function __construct(private readonly TechnicalImageRepository $technicalCommandRepository, private readonly AttachmentRepository $attachmentRepository)
    {
    }


    public function __invoke(DeleteTechnicalImageCommand $command): void
    {
        $technicalImage = $this->technicalCommandRepository->find($command->getId());
        if (!$technicalImage) {
            throw new InvalidArgumentException('Technical image not found');
        }
        if ($technicalImage->getAttachment()) {
            $this->attachmentRepository->delete($technicalImage->getAttachment());
        }
        $this->technicalCommandRepository->remove($technicalImage);
        $this->technicalCommandRepository->flush();
    }
}
