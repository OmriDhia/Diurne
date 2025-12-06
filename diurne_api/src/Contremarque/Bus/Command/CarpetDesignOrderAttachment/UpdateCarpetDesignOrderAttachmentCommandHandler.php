<?php

namespace App\Contremarque\Bus\Command\CarpetDesignOrderAttachment;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderAttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateCarpetDesignOrderAttachmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly CarpetDesignOrderAttachmentRepository $carpetDesignOrderAttachmentRepository, private readonly AttachmentRepository $attachmentRepository, private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository)
    {
    }

    public function __invoke(UpdateCarpetDesignOrderAttachmentCommand $command): UpdateCarpetDesignOrderAttachmentCommandResponse
    {
        $carpetDesignOrderAttachment = $this->carpetDesignOrderAttachmentRepository->find($command->id);

        if (!$carpetDesignOrderAttachment) {
            throw new Exception('CarpetDesignOrderAttachment not found');
        }

        if (null !== $command->attachmentId) {
            $attachment = $this->attachmentRepository->find($command->attachmentId);
            if (!$attachment) {
                throw new Exception('Attachment not found');
            }
            $carpetDesignOrderAttachment->setAttachment($attachment);
        }
        if (null !== $command->carpetDesignOrderId) {
            $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->carpetDesignOrderId);
            if (!$carpetDesignOrder) {
                throw new Exception('CarpetDesignOrder not found');
            }
            $carpetDesignOrderAttachment->setCarpetDesignOrder($carpetDesignOrder);
        }
        $this->entityManager->persist($carpetDesignOrderAttachment);

        $this->entityManager->flush();

        return new UpdateCarpetDesignOrderAttachmentCommandResponse($carpetDesignOrderAttachment);
    }
}
