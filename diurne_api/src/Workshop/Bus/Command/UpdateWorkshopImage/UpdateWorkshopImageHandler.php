<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopImage;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\Attachment;
use App\Contremarque\Entity\Location;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Repository\WorkshopImageRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateWorkshopImageHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param WorkshopImageRepository $workshopImageRepository
     */
    public function __construct(
        private EntityManagerInterface  $entityManager,
        private WorkshopImageRepository $workshopImageRepository
    )
    {
    }

    /**
     * @param UpdateWorkshopImageCommand $command
     * @return UpdateWorkshopImageResponse
     * @throws DuplicateValidationResourceException
     */
    public function __invoke(UpdateWorkshopImageCommand $command): UpdateWorkshopImageResponse
    {
        $workshopImage = $this->workshopImageRepository->find($command->id);
        if ($workshopImage->getFileName() !== $command->fileName) {
            $existingImageName = $this->workshopImageRepository->findOneByName(['file_name' => $command->fileName]);
            if ($existingImageName !== null && $existingImageName->getId() !== $existingImageName->getId()) {
                throw new DuplicateValidationResourceException('A file with this name already exists');
            }
        }
        if (!$workshopImage) {
            throw new ResourceNotFoundException ();
        }

        if ($command->fileName !== null) {
            $workshopImage->setFileName($command->fileName);
        }

        if ($command->idImageType !== null) {
            $workshopImage->setIdImageType($command->idImageType);
        }

        if ($command->format !== null) {
            $workshopImage->setFormat($command->format);
        }
        if ($command->locationId !== null) {
            $location = $this->entityManager->getReference(Location::class, $command->locationId);
            $workshopImage->setLocationId($location);
        }
        if ($command->workshopOrderId !== null) {
            $workshopOrder = $this->entityManager->getReference(WorkshopOrder::class, $command->workshopOrderId);
            $workshopImage->setWorkshopOrder($workshopOrder);
        }
        if ($command->attachmentId !== null) {
            $attachment = $this->entityManager->getReference(Attachment::class, $command->attachmentId);
            $workshopImage->setAttachmentId($attachment);
        }
        $workshopImage->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        return new UpdateWorkshopImageResponse($workshopImage);
    }
}