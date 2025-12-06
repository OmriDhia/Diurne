<?php

namespace App\Contremarque\Bus\Command\DeleteImage;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\ImageAttachmentRepository;
use App\Contremarque\Repository\ImageRepository;

class DeleteImageCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ImageRepository           $imageRepository,
        private readonly ImageAttachmentRepository $imageAttachmentRepository
    )
    {
    }

    public function __invoke(DeleteImageCommand $command): DeleteImageResponse
    {
        if (empty($command->getIds())) {
            throw new InvalidArgumentException('No image ids provided');
        }
        foreach ($command->getIds() as $id) {
            $image = $this->imageRepository->find($id);
            if (null === $image) {
                throw new InvalidArgumentException('Image not found');
            }
            $this->imageAttachmentRepository->removeByImage($image);
            $this->imageRepository->remove($image);
        }

        return new DeleteImageResponse('Image deleted successfully');
    }
}
