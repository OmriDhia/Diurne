<?php

namespace App\Contremarque\Bus\Command\AttachmentUpload;

use InvalidArgumentException;
use RuntimeException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\Attachment;
use App\Setting\Repository\AttachmentTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadFileHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SluggerInterface $slugger,
        private readonly string $uploadDirectory,
        private readonly AttachmentTypeRepository $attachmentTypeRepository
    ) {}

    public function __invoke(UploadFileCommand $command): Attachment
    {
        $attachmentType = null;

        if (null !== $command->getAttachmentTypeId()) {
            $attachmentType = $this->attachmentTypeRepository->find((int) $command->getAttachmentTypeId());
        }

        if (empty($attachmentType)) {
            throw new InvalidArgumentException('AttachmentType not found');
        }

        if ($command->getFile()) {
            return $this->handleLocalFile($command->getFile(), $attachmentType);
        }

        if ($command->getDistantFilePath()) {
            return $this->handleDistantFile($command->getDistantFilePath(), $attachmentType);
        }

        throw new InvalidArgumentException('No file or distant file path provided');
    }

    private function handleLocalFile(UploadedFile $file, $attachmentType): Attachment
    {
        if (!$this->isImage($file)) {
            throw new InvalidArgumentException('Uploaded file is not a valid image');
        }
        // Check file existence before getting size
        if (!is_readable($file->getPathname())) {
            throw new RuntimeException('Uploaded file is no longer available.');
        }

        // Check file size (3 MB = 3 * 1024 * 1024 bytes)
        if ($file->getSize() > 3 * 1024 * 1024) {
            throw new InvalidArgumentException('Uploaded file exceeds the maximum size of 3 MB');
        }
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $extension = $file->guessExtension();
        $uniqueFilename = $safeFilename . '-' . uniqid() . '.' . $extension;
        $uploadDirectory = $this->uploadDirectory;
        // Create the directory if it doesn't exist
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0775, true);
        }

        try {
            $file->move($this->uploadDirectory, $uniqueFilename);
        } catch (FileException $e) {
            throw new RuntimeException('Failed to upload file', 0, $e);
        }

        $attachment = new Attachment();
        $attachment->setFile($uniqueFilename);
        $attachment->setPath($this->uploadDirectory);
        $attachment->setAttachmentType($attachmentType);
        $attachment->setExtension($extension);
        $attachment->setSize(filesize($this->uploadDirectory . '/' . $uniqueFilename));
        $attachment->setFromDistantServer(false);

        $this->em->persist($attachment);
        $this->em->flush();

        return $attachment;
    }

    private function handleDistantFile(string $distantFilePath, $attachmentType): Attachment
    {
        $extension = pathinfo($distantFilePath, PATHINFO_EXTENSION);

        $attachment = new Attachment();
        $attachment->setFile(null); // No local file
        $attachment->setPath($distantFilePath); // Distant file path
        $attachment->setExtension($extension);
        $attachment->setAttachmentType($attachmentType);
        $attachment->setFromDistantServer(true);

        $this->em->persist($attachment);
        $this->em->flush();

        return $attachment;
    }

    private function isImage(UploadedFile $file): bool
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        return in_array($file->getMimeType(), $allowedMimeTypes, true)
            && in_array($file->guessExtension(), $allowedExtensions, true);
    }
}
