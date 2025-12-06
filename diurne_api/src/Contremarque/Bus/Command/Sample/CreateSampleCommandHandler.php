<?php

namespace App\Contremarque\Bus\Command\Sample;

use DateTimeImmutable;
use Exception;
use RuntimeException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\Sample;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\ImageRepository;
use App\Contremarque\Repository\LocationRepository;
use App\Contremarque\Repository\SampleRepository;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\ModelRepository;
use App\Setting\Repository\QualityRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateSampleCommandHandler implements CommandHandler
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
        private readonly AttachmentRepository $attachmentRepository,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * @throws NotFoundHttpException If a required related entity is not found
     * @throws BadRequestHttpException If validation fails
     */
    public function __invoke(CreateSampleCommand $command): SampleResponse
    {
        $errors = [];

        // Fetch related entities and validate
        $location = $this->getEntityOrFail($command->getLocationId(), $this->locationRepository, 'Location');
        if (empty($status)) {
            $status = $this->carpetStatusRepository->getStatusByName('Non transmis');
        }
        $quality = $this->getEntityOrFail($command->getQualityId(), $this->qualityRepository, 'Quality');
        if ($command->getCarpetDesignOrderId()) {
            $carpetDesignOrder = $this->getEntityOrFail($command->getCarpetDesignOrderId(), $this->carpetDesignOrderRepository, 'Carpet Design Order');
        } else {
            // Create new minimal CarpetDesignOrder with just required fields
            $carpetDesignOrder = new CarpetDesignOrder();

            $carpetDesignOrder->setStatus($status);
            $carpetDesignOrder->setTransmitionDate(new \DateTimeImmutable());

            // If location is required for CarpetDesignOrder
            $carpetDesignOrder->setLocation($location);
            // Flag this as a container for samples only
            $carpetDesignOrder->setIsSampleContainer(true);
            // Persist the new CarpetDesignOrder first
            $this->carpetDesignOrderRepository->persist($carpetDesignOrder);
            $this->carpetDesignOrderRepository->flush();
        }

        $collection = $command->getCollectionId()
            ? $this->getOptionalEntity($command->getCollectionId(), $this->carpetCollectionRepository, 'Collection', $errors)
            : null;

        $model = $command->getModelId()
            ? $this->getOptionalEntity($command->getModelId(), $this->modelRepository, 'Model', $errors)
            : null;



        // Validate attachments
        $attachments = [];
        if ($command->getAttachmentIds()) {
            foreach ($command->getAttachmentIds() as $attachmentId) {
                $attachment = $this->attachmentRepository->find($attachmentId);
                if (!$attachment) {
                    $this->logger->warning('Attachment not found for ID: {id}', ['id' => $attachmentId]);
                    $errors[] = "Attachment with ID {$attachmentId} not found";
                    continue;
                }
                if ($attachment->getSample() !== null) {
                    $this->logger->warning('Attachment already attached to another sample: {id}', ['id' => $attachmentId]);
                    $errors[] = "Attachment with ID {$attachmentId} is already attached to another sample";
                    continue;
                }
                $attachments[] = $attachment;
            }
        }

        // If there are errors, throw an exception
        if (!empty($errors)) {
            throw new BadRequestHttpException(implode(', ', $errors));
        }

        // Create the Sample entity
        $sample = new Sample();
        $sample->setLocation($location);
        $sample->setStatus($status);
        $sample->setQuality($quality);
        $sample->setCarpetDesignOrder($carpetDesignOrder);
        $sample->setCollection($collection);
        $sample->setModel($model);
        $sample->setDiCommandNumber($command->getDiCommandNumber() ?? '');
        $sample->setRn($command->getRn());
        $sample->setTransmissionDate(
            $command->getTransmissionDate() ? new DateTimeImmutable($command->getTransmissionDate()) : null
        );
        $sample->setCustomerComment($command->getCustomerComment());
        $sample->setDimension($command->getDimension());

        if ($command->getImageIds()) {
            foreach ($command->getImageIds() as $imageId) {
                $image = $this->imageRepository->find($imageId);
                if (!$image) {
                    $this->logger->warning('Image not found for ID: {id}', ['id' => $imageId]);
                    $errors[] = "Image with ID {$imageId} not found";
                    continue;
                }
                // Remove the check for image being attached to another sample
                // since ManyToMany allows sharing images between samples
                $sample->addImage($image);
            }
        }

        // Add attachments
        foreach ($attachments as $attachment) {
            $sample->addAttachment($attachment);
        }

        // Persist and flush within a transaction
        $this->sampleRepository->getEntityManager()->beginTransaction();
        try {
            $this->sampleRepository->persist($sample);
            $this->sampleRepository->flush();
            $this->sampleRepository->getEntityManager()->commit();
            $this->logger->info('Sample created with ID: {id}', ['id' => $sample->getId()]);
        } catch (Exception $e) {
            $this->sampleRepository->getEntityManager()->rollback();
            $this->logger->error('Failed to create sample: {message}', ['message' => $e->getMessage()]);
            throw new RuntimeException('Failed to create sample: ' . $e->getMessage(), 0, $e);
        }

        return new SampleResponse($sample);
    }

    /**
     * Helper method to fetch a required entity or throw an exception.
     *
     * @template T of object
     * @param int|null $id
     * @param object $repository
     * @param string $entityName
     * @return T
     * @throws NotFoundHttpException
     */
    private function getEntityOrFail(?int $id, object $repository, string $entityName): object
    {
        if ($id === null) {
            throw new NotFoundHttpException("{$entityName} ID cannot be null");
        }

        $entity = $repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException("{$entityName} with ID {$id} not found");
        }

        return $entity;
    }

    /**
     * Helper method to fetch an optional entity, adding to errors if not found.
     *
     * @param int $id
     * @param object $repository
     * @param string $entityName
     * @param array<string> $errors
     * @return object|null
     */
    private function getOptionalEntity(int $id, object $repository, string $entityName, array &$errors): ?object
    {
        $entity = $repository->find($id);
        if (!$entity) {
            $this->logger->warning("{$entityName} not found for ID: {id}", ['id' => $id]);
            $errors[] = "{$entityName} with ID {$id} not found";
            return null;
        }
        return $entity;
    }
}
