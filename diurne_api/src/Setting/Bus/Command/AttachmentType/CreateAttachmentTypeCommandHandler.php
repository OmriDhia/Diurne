<?php

namespace App\Setting\Bus\Command\AttachmentType;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\AttachmentType;
use App\Setting\Repository\AttachmentTypeRepository;

class CreateAttachmentTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly AttachmentTypeRepository $attachmentTypeRepository)
    {
    }

    public function __invoke(CreateAttachmentTypeCommand $command): AttachmentTypeResponse
    {
        $attachmentType = new AttachmentType();
        $attachmentType->setName($command->getName());

        $this->attachmentTypeRepository->persist($attachmentType);
        $this->attachmentTypeRepository->flush();

        return new AttachmentTypeResponse($attachmentType);
    }
}
