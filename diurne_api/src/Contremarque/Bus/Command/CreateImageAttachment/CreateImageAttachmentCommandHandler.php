<?php

namespace App\Contremarque\Bus\Command\CreateImageAttachment;

use RuntimeException;
use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\ImageAttachment;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateImageAttachmentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ImageRepository $imageRepository,
        private readonly AttachmentRepository $attachmentRepository
    ) {
    }

    public function __invoke(CreateImageAttachmentCommand $command): CreateImageAttachmentResponse
    {
        $image = $this->imageRepository->find($command->imageId);
        $attachment = $this->attachmentRepository->find($command->attachmentId);

        if (!$image || !$attachment) {
            throw new ValidationException(['Invalid Image or Attachment ID']);
        }

        $diAttachment = new ImageAttachment();
        $diAttachment->setImage($image);
        $diAttachment->setAttachment($attachment);

        if ('Vignette' === $image->getImageType()->getName()) {
            $this->resizeImage($attachment->getPath().'/'.$attachment->getFile(), 90, 90);
        }
        $this->entityManager->persist($diAttachment);
        $this->entityManager->flush();

        return new CreateImageAttachmentResponse($diAttachment);
    }

    private function resizeImage(string $filePath, int $width, int $height): void
    {
        // Get the original image size
        [$originalWidth, $originalHeight] = getimagesize($filePath);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $filename = pathinfo($filePath, PATHINFO_FILENAME);

        if (!$originalWidth || !$originalHeight) {
            throw new RuntimeException('Unable to get image size');
        }

        // Create a new blank image with desired dimensions
        $resizedImage = imagecreatetruecolor($width, $height);

        // Load the image based on its extension
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $originalImage = imagecreatefromjpeg($filePath);
                break;
            case 'png':
                $originalImage = imagecreatefrompng($filePath);
                // Preserve transparency for PNG
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                break;
            case 'gif':
                $originalImage = imagecreatefromgif($filePath);
                break;
            case 'webp':
                $originalImage = imagecreatefromwebp($filePath);
                break;
            default:
                throw new InvalidArgumentException('Unsupported image format');
        }

        // Resize the image
        imagecopyresampled(
            $resizedImage,
            $originalImage,
            0, 0, 0, 0,
            $width, $height,
            $originalWidth, $originalHeight
        );

        // Define the /resized directory
        $resizedDirectory = dirname($filePath).'/resized';

        // Check if the resized directory exists, if not, create it
        if (!is_dir($resizedDirectory)) {
            if (!mkdir($resizedDirectory, 0755, true) && !is_dir($resizedDirectory)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $resizedDirectory));
            }
        } else {
            // Check if the directory is writable
            if (!is_writable($resizedDirectory)) {
                // Attempt to correct permissions
                if (!chmod($resizedDirectory, 0755)) {
                    throw new RuntimeException(sprintf('Directory "%s" is not writable and permission change failed', $resizedDirectory));
                }
            }
        }

        // Save the resized image to the /resized directory
        $resizedFilePath = $resizedDirectory.'/'.$filename.'_'.$width.'x'.$height.'.'.$extension;

        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($resizedImage, $resizedFilePath);
                break;
            case 'png':
                imagepng($resizedImage, $resizedFilePath);
                break;
            case 'gif':
                imagegif($resizedImage, $resizedFilePath);
                break;
            case 'webp':
                imagewebp($resizedImage, $resizedFilePath);
                break;
        }

        // Free up memory
        imagedestroy($originalImage);
        imagedestroy($resizedImage);
    }
}
