<?php

namespace App\Setting\Bus\Command\AttachmentType;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Repository\AttachmentTypeRepository;
use Doctrine\ORM\EntityNotFoundException;

class UpdateAttachmentTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly AttachmentTypeRepository $attachmentTypeRepository)
    {
    }

    public function __invoke(UpdateAttachmentTypeCommand $command): AttachmentTypeResponse
    {
        $attachmentType = $this->attachmentTypeRepository->find($command->getId());

        if (!$attachmentType) {
            throw new EntityNotFoundException('AttachmentType not found');
        }

        if ($command->getName()) {
            $attachmentType->setName($command->getName());
        }

        $this->attachmentTypeRepository->persist($attachmentType);
        $this->attachmentTypeRepository->flush();

        return new AttachmentTypeResponse($attachmentType);
    }
}
