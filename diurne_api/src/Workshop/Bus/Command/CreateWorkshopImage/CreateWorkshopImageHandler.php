<?php

namespace App\Workshop\Bus\Command\CreateWorkshopImage;


use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Contremarque\Entity\Attachment;
use App\Contremarque\Entity\Location;
use App\Workshop\Entity\WorkshopImage;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Repository\WorkshopImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;


class CreateWorkshopImageHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface  $entityManager,
        private WorkshopImageRepository $workshopImageRepository
    )
    {
    }

    /**
     * @throws ORMException
     * @throws DuplicateValidationResourceException
     */
    public function __invoke(CreateWorkshopImageCommand $command): WorkshopImageResponse
    {
        $existingImageName = $this->workshopImageRepository->findOneByName(['file_name' => $command->fileName]);
        if ($existingImageName !== null) {
            throw new DuplicateValidationResourceException("A file with this name already exists");
        }
        $workshopImage = new WorkshopImage();

        $workshopImage->setFileName($command->fileName);
        $workshopImage->setIdImageType($command->idImageType);
        $workshopImage->setFormat($command->format);

        $location = $this->entityManager->getReference(Location::class, $command->locationId);
        $workshopImage->setLocationId($location);

        $workshopOrder = $this->entityManager->getReference(WorkshopOrder::class, $command->workshopOrderId);
        $workshopImage->setWorkshopOrder($workshopOrder);

        $attachment = $this->entityManager->getReference(Attachment::class, $command->attachmentId);
        $workshopImage->setAttachmentId($attachment);

        $workshopImage->setCreatedAt($command->createdAt ? new \DateTime($command->createdAt) : new \DateTime());
        $workshopImage->setUpdatedAt($command->updatedAt ? new \DateTime($command->updatedAt) : new \DateTime());

        $this->entityManager->persist($workshopImage);
        $this->entityManager->flush();
        return new WorkshopImageResponse($workshopImage);
    }
}