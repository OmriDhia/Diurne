<?php

namespace App\Setting\Bus\Command\AttachmentType;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Repository\AttachmentTypeRepository;
use Doctrine\ORM\EntityNotFoundException;

class DeleteAttachmentTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly AttachmentTypeRepository $attachmentTypeRepository)
    {
    }

    public function __invoke(DeleteAttachmentTypeCommand $command): void
    {
        $attachmentType = $this->attachmentTypeRepository->find($command->getId());

        if (!$attachmentType) {
            throw new EntityNotFoundException('AttachmentType not found');
        }

        $this->attachmentTypeRepository->remove($attachmentType);
        $this->attachmentTypeRepository->flush();
    }
}
