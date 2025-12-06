<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteAttachment;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderAttachmentRepository;
use App\Contremarque\Repository\DiAttachmentRepository;
use App\Contremarque\Repository\ImageAttachmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteAttachmentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly AttachmentRepository                  $attachmentRepository,
        private readonly DiAttachmentRepository                $diAttachmentRepository,
        private readonly CarpetDesignOrderAttachmentRepository $carpetDesignOrderAttachmentRepository,
        private readonly ImageAttachmentRepository             $imageAttachmentRepository,
        private readonly EntityManagerInterface                $entityManager
    )
    {
    }

    public function __invoke(DeleteAttachmentCommand $command): void
    {
        $attachment = $this->attachmentRepository->find($command->getId());

        if (null === $attachment) {
            throw new ResourceNotFoundException('Attachment not found');
        }
        $this->attachmentRepository->delete($attachment);
        $this->entityManager->remove($attachment);
        $this->entityManager->flush();
    }
}
