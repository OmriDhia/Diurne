<?php

namespace App\Contremarque\Bus\Command\CarpetDesignOrderImages;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderAttachmentRepository;
use App\Contremarque\Repository\DiAttachmentRepository;
use App\Contremarque\Repository\ImageAttachmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteCarpetDesignOrderImagesCommandHandler implements CommandHandler
{
    public function __construct(private readonly AttachmentRepository      $attachmentRepository
        , private readonly CarpetDesignOrderAttachmentRepository           $carpetDesignOrderAttachmentRepository,
                                private readonly DiAttachmentRepository    $diAttachmentRepository,
                                private readonly ImageAttachmentRepository $imageAttachmentRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(DeleteCarpetDesignOrderImagesCommand $command): void
    {
        $imageIds = $command->getImageIds();

        foreach ($imageIds as $id) {
            $image = $this->attachmentRepository->find((int)$id);

            if (!$image) {
                throw new InvalidArgumentException(sprintf('Image with id %d not found', $id));
            }

            // Delete related CarpetDesignOrderAttachment entities
            $this->carpetDesignOrderAttachmentRepository->deleteByAttachment($image);
            // Delete related DiAttachment entities
            $this->diAttachmentRepository->deleteByAttachment($image);
            // Delete related ImageAttachment entities
            $this->imageAttachmentRepository->deleteByAttachment($image);

            $this->entityManager->remove($image);
        }

        $this->entityManager->flush();
    }
}
