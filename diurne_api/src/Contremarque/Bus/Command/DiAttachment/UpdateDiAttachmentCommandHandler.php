<?php

namespace App\Contremarque\Bus\Command\DiAttachment;

use Exception;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\ProjectDiRepository;
use App\Repository\DiAttachmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateDiAttachmentCommandHandler
{
    private readonly DiAttachmentRepository $diAttachmentRepository;

    public function __construct(
        DiAttachmentRepository $diAttachmentRepository,
        private readonly AttachmentRepository $attachmentRepository,
        private readonly ProjectDiRepository $projectDiRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->diAttachmentRepository = $diAttachmentRepository;
    }

    public function handle(UpdateDiAttachmentCommand $command): UpdateDiAttachmentResponse
    {
        // Find existing DiAttachment entity
        $diAttachment = $this->diAttachmentRepository->find($command->getDiAttachmentId());
        if (!$diAttachment) {
            throw new Exception('DiAttachment not found');
        }

        // Find the related Attachment and ProjectDi entities
        $attachment = $this->attachmentRepository->find($command->getAttachmentId());
        if (!$attachment) {
            throw new Exception('Attachment not found');
        }

        $di = $this->projectDiRepository->find($command->getDiId());
        if (!$di) {
            throw new Exception('ProjectDi not found');
        }

        // Update the DiAttachment entity
        $diAttachment->setAttachment($attachment);
        $diAttachment->setDi($di);

        // Persist and flush changes
        $this->entityManager->persist($diAttachment);
        $this->entityManager->flush();

        return new UpdateDiAttachmentResponse($diAttachment);
    }
}
