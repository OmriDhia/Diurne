<?php

namespace App\Contremarque\Bus\Command\DiAttachment;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\DiAttachment;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\ProjectDiRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateDiAttachmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly AttachmentRepository $attachmentRepository, private readonly ProjectDiRepository $projectDiRepository)
    {
    }

    public function __invoke(CreateDiAttachmentCommand $command): CreateDiAttachmentCommandResponse
    {
        $attachment = $this->attachmentRepository->find($command->attachmentId);
        $di = $this->projectDiRepository->find($command->diId);

        if (!$attachment || !$di) {
            throw new Exception('Attachment or ProjectDi not found');
        }

        $diAttachment = new DiAttachment();
        $diAttachment->setAttachment($attachment);
        $diAttachment->setDi($di);

        $this->entityManager->persist($diAttachment);
        $this->entityManager->flush();

        return new CreateDiAttachmentCommandResponse($diAttachment);
    }
}
