<?php

namespace App\Contremarque\Bus\Command\Sample;

use DateTimeImmutable;
use Exception;
use RuntimeException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\Sample;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\ValueObject\Dimension;
use App\Contremarque\Repository\SampleRepository;
use App\Contremarque\Repository\LocationRepository;
use App\Contremarque\Repository\ImageRepository;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\ModelRepository;
use App\Setting\Repository\QualityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateSampleCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SampleRepository $sampleRepository,
        private readonly LocationRepository $locationRepository,
        private readonly CarpetCollectionRepository $carpetCollectionRepository,
        private readonly ModelRepository $modelRepository,
        private readonly CarpetStatusRepository $carpetStatusRepository,
        private readonly QualityRepository $qualityRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly ImageRepository $imageRepository,
        private readonly AttachmentRepository $attachmentRepository
    ) {}

    public function __invoke(UpdateSampleCommand $command): SampleResponse
    {
        $sample = $this->sampleRepository->find($command->id);
        if (!$sample) {
            throw new NotFoundHttpException("Sample with ID {$command->id} not found");
        }

        if ($command->statusId !== null) {
            $status = $this->getEntityOrFail($command->statusId, $this->carpetStatusRepository, 'Status');
            $sample->setStatus($status);
        }

        if ($command->locationId !== null) {
            $location = $this->getEntityOrFail($command->locationId, $this->locationRepository, 'Location');
            $sample->setLocation($location);
        }
        if ($command->collectionId !== null) {
            $collection = $this->getEntityOrFail($command->collectionId, $this->carpetCollectionRepository, 'Collection');
            $sample->setCollection($collection);
        }
        if ($command->modelId !== null) {
            $model = $this->getEntityOrFail($command->modelId, $this->modelRepository, 'Model');
            $sample->setModel($model);
        }
        if ($command->qualityId !== null) {
            $quality = $this->getEntityOrFail($command->qualityId, $this->qualityRepository, 'Quality');
            $sample->setQuality($quality);
        }
        if ($command->dimension !== null) {
            $sample->setDimension($command->dimension);
        }


        // Update images (ManyToMany)
        if ($command->imageIds !== null) {
            $currentImages = $sample->getImages();
            $newImageIds = $command->imageIds;

            // Remove images not in new list
            foreach ($currentImages as $image) {
                if (!in_array($image->getId(), $newImageIds)) {
                    $sample->removeImage($image);
                }
            }

            // Add new images
            $currentImageIds = $currentImages->map(fn($image) => $image->getId())->toArray();
            foreach ($newImageIds as $imageId) {
                if (!in_array($imageId, $currentImageIds)) {
                    $image = $this->imageRepository->find($imageId);
                    if (!$image) {
                        throw new NotFoundHttpException("Image with ID {$imageId} not found");
                    }
                    $sample->addImage($image);
                }
            }
        }

        // Update attachments (OneToMany)
        if ($command->attachmentIds !== null) {
            foreach ($sample->getAttachments() as $attachment) {
                $sample->removeAttachment($attachment);
            }
            foreach ($command->attachmentIds as $attachmentId) {
                $attachment = $this->attachmentRepository->find($attachmentId);
                if (!$attachment) {
                    throw new NotFoundHttpException("Attachment with ID {$attachmentId} not found");
                }
                if ($attachment->getSample() !== null && $attachment->getSample()->getId() !== $sample->getId()) {
                    throw new BadRequestHttpException("Attachment with ID {$attachmentId} is already attached to another sample");
                }
                $sample->addAttachment($attachment);
            }
        }

        // Persist changes
        $this->sampleRepository->getEntityManager()->beginTransaction();
        try {
            $this->sampleRepository->persist($sample);
            $this->sampleRepository->flush();
            $this->sampleRepository->getEntityManager()->commit();
        } catch (Exception $e) {
            $this->sampleRepository->getEntityManager()->rollback();
            throw new RuntimeException('Failed to update sample: ' . $e->getMessage(), 0, $e);
        }

        return new SampleResponse($sample);
    }

    private function getEntityOrFail(int $id, object $repository, string $entityName): object
    {
        $entity = $repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException("{$entityName} with ID {$id} not found");
        }
        return $entity;
    }
}
