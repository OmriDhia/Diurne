<?php

declare(strict_types=1);

namespace App\Contremarque\Service;

use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\ImageAttachment;
use App\Contremarque\Repository\ImageAttachmentRepository;

class ImageProvider
{
    public function __construct(
        private readonly ImageAttachmentRepository $imageAttachmentRepository
    ) {}

    /**
     * Get the image path for a given CarpetDesignOrder and type.
     */
    public function getImagePath(CarpetDesignOrder $order, string $type): ?string
    {
        $imageAttachment = $this->imageAttachmentRepository->findByType($order, $type);

        return $imageAttachment ? $this->getOriginalPath($imageAttachment) : null;
    }

    /**
     * Get the vignette path (always the original).
     */
    public function getVignettePath(CarpetDesignOrder $order): ?string
    {
        $imageAttachment = $this->imageAttachmentRepository->findByType($order, 'vignette');

        return $imageAttachment ? $this->getOriginalPath($imageAttachment) : null;
    }

    /**
     * Generate and retrieve the resized vignette path (_90x90).
     */
    public function getResizedVignettePath(CarpetDesignOrder $order): ?string
    {
        $imageAttachment = $this->imageAttachmentRepository->findByType($order, 'vignette');

        return $imageAttachment ? $this->generateResizedPath($imageAttachment) : null;
    }

    /**
     * Generate the resized vignette path (_90x90).
     */
    private function generateResizedPath(ImageAttachment $attachment): ?string
    {
        $file = $attachment->getAttachment()->getFile();
        $resizedDirectory = $attachment->getAttachment()->getPath() . '/resized/';

        $extensionPatterns = ['.jpg', '.jpeg', '.png', '.webp'];

        foreach ($extensionPatterns as $pattern) {
            if (str_contains((string) $file, $pattern)) {
                return $resizedDirectory . str_replace($pattern, '_90x90' . $pattern, $file);
            }
        }

        return null; // Return null if no valid extension is found
    }

    /**
     * Get the original image path.
     */
    private function getOriginalPath(ImageAttachment $attachment): string
    {
        return $attachment->getAttachment()->getPath() . '/' . $attachment->getAttachment()->getFile();
    }
}
