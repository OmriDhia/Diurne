<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetDesignOrderAttachment;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ApiException;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\CarpetDesignOrderAttachment;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Handles the command to create a CarpetDesignOrderAttachment.
 */
final readonly class CreateCarpetDesignOrderAttachmentCommandHandler implements CommandHandler
{
    private const STATUS_IN_PROGRESS = 'En cours';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AttachmentRepository $attachmentRepository,
        private CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws ValidationException
     */
    public function __invoke(CreateCarpetDesignOrderAttachmentCommand $command): CreateCarpetDesignOrderAttachmentCommandResponse
    {
        $this->entityManager->beginTransaction();

        try {
            // Fetch and validate entities
            $attachment = $this->attachmentRepository->find((int) $command->attachmentId);
            $carpetDesignOrder = $this->carpetDesignOrderRepository->find((int) $command->carpetDesignOrderId);

            // Validate the command
            $this->validateCommand($attachment, $carpetDesignOrder);

            // Create the CarpetDesignOrderAttachment
            $carpetDesignOrderAttachment = new CarpetDesignOrderAttachment();
            $carpetDesignOrderAttachment
                ->setAttachment($attachment)
                ->setCarpetDesignOrder($carpetDesignOrder);

            // Persist and flush
            $this->entityManager->persist($carpetDesignOrderAttachment);
            $this->entityManager->flush();
            $this->entityManager->commit();

            $this->logger->info('CarpetDesignOrderAttachment created successfully', [
                'attachmentId' => $command->attachmentId,
                'carpetDesignOrderId' => $command->carpetDesignOrderId,
            ]);

            return new CreateCarpetDesignOrderAttachmentCommandResponse($carpetDesignOrderAttachment);
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->logger->error('Failed to create CarpetDesignOrderAttachment', [
                'attachmentId' => $command->attachmentId,
                'carpetDesignOrderId' => $command->carpetDesignOrderId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateCommand(?object $attachment, ?object $carpetDesignOrder): void
    {
        $errors = [];

        // Validate attachment
        if ($attachment === null) {
            $errors[] = 'Attachment not found';
        }

        // Validate carpet design order
        if ($carpetDesignOrder === null) {
            $errors[] = 'CarpetDesignOrder not found';
        } else {
            $this->validateCarpetDesignOrder($carpetDesignOrder, $errors);
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                throw new ApiException($error, 401);
            }
        }
    }

    private function validateCarpetDesignOrder(object $carpetDesignOrder, array &$errors): void
    {
        // Validate status existence
        $status = $carpetDesignOrder->getStatus();
        if ($status === null) {
            $errors[] = 'Carpet designer order without status';
            return;
        }

        // Validate status value
        // if ($status->getName() !== self::STATUS_IN_PROGRESS) {
        //     $errors[] = sprintf('Carpet designer order status is "%s", expected "%s"', $status->getName(), self::STATUS_IN_PROGRESS);
        // }
    }
}
