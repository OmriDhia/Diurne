<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetDesignOrder;

use InvalidArgumentException;
use DateTimeImmutable;
use Throwable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Messenger\EmailMessage;
use App\Common\Service\MailerService;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\LocationRepository;
use App\Contremarque\Repository\ProjectDiRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateCarpetDesignOrderCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CarpetDesignOrderRepository   $carpetDesignOrderRepository,
        private readonly LocationRepository            $locationRepository,
        private readonly CarpetStatusRepository        $carpetStatusRepository,
        private readonly ProjectDiRepository           $projectDiRepository,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly CustomerRepository            $customerRepository,
        private readonly MailerService                 $mailerService,
        private readonly MessageBusInterface           $messageBus,
        private readonly QuoteDetailRepository         $quoteDetailRepository,
    )
    {
    }

    public function __invoke(UpdateCarpetDesignOrderCommand $command): CarpetDesignOrderResponse
    {
        $errors = [];
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->getId());
        if (!$carpetDesignOrder instanceof CarpetDesignOrder) {
            throw new ResourceNotFoundException('CarpetDesignOrder not found.');
        }

        if (null !== $command->getLocationId()) {
            $location = $this->locationRepository->find($command->getLocationId());
            $carpetDesignOrder->setLocation($location);
        }

        if (null !== $command->getDesignerAssignments()) {
            // Handle designer assignments (if needed)
        }

        if (null !== $command->getModelName()) {
            $carpetDesignOrder->setModelName($command->getModelName());
        }

        if (null !== $command->getVariation()) {
            $carpetDesignOrder->setVariation($command->getVariation());
        }

        if (null !== $command->getJpeg()) {
            $carpetDesignOrder->setJpeg($command->getJpeg());
        }

        if (null !== $command->getImpression()) {
            $carpetDesignOrder->setImpression($command->getImpression());
        }

        if (null !== $command->getImpressionBarreDeLaine()) {
            $carpetDesignOrder->setImpressionBarreDeLaine($command->getImpressionBarreDeLaine());
        }

        if (null !== $command->getStatusId()) {
            $status = $this->carpetStatusRepository->find((int)$command->getStatusId());
            $TransmittedStatus = $this->carpetStatusRepository->getStatusByName('Transmis');
            $transmissionCheck = $this->canBeTransmitted($carpetDesignOrder);
            $finishedStatus = $this->carpetStatusRepository->getStatusByName('Fini');
            $finishCheck = $this->validateFinishingCarpet($carpetDesignOrder);
            if (!$transmissionCheck['success']) {
                array_merge($errors, $transmissionCheck['errors']);
                throw new InvalidArgumentException(implode(' ', $transmissionCheck['errors']));
            }
            if ($TransmittedStatus === $status && $transmissionCheck['success']) {
                $carpetDesignOrder->setTransmitionDate(new DateTimeImmutable());
                $carpetDesignOrder->setStatus($status);
                $carpetDesignOrder->getProjectDi()->setTransmittedToStudio(true);
            } else {
                $carpetDesignOrder->setTransmitionDate(null);
            }

            if ($finishedStatus === $status && !$finishCheck) {
                throw new InvalidArgumentException('Missing required images: Vigniette and/or Légende A4/A3.');
            }

            if ($finishedStatus === $status && $finishCheck) {
                $commercial = $this->customerRepository->findCommercialByCarpetDesignOrder($carpetDesignOrder);
                $carpetDesignOrder->setStatus($status);
                if ($commercial) {
                    foreach ($carpetDesignOrder->getImages() as $image) {
                        $images[] = $image->toArray();
                    }

                    if ($carpetDesignOrder) {
                        $designers = $carpetDesignOrder->getDesigners();
                        $lastDesigner = $designers->last();

                        $emailData = [
                            'contremarqueId' => $carpetDesignOrder->getProjectDi()->getContremarque()->getId(),
                            'diNumber' => $carpetDesignOrder->getProjectDi()->getDemandeNumber(),
                            'imageId' => $carpetDesignOrder->getId(),
                            'designerName' => $lastDesigner->getDesigner()->getEmail(),
                            'location' => $carpetDesignOrder->getLocation()->getDescription(),
                            'collection' => $carpetDesignOrder->getCarpetSpecification()->getCollection()->getReference(),
                            'model' => $carpetDesignOrder->getCarpetSpecification()->getModel()->getCode(),
                        ];
                        foreach ($carpetDesignOrder->getImages() as $image) {
                            $imageAttachment = $image->getImageAttachment();

                            if ($imageAttachment) {
                                $emailData['images'][] = $imageAttachment->getAttachment()->getPath() . '/' . $imageAttachment->getAttachment()->getFile();
                            }
                        }
                        try {
                            $emailMessage = new EmailMessage(
                                $commercial->getEmail(),
                                'Carpet Design Order Finalized',
                                'emails/carpet_design_order_finalized.html.twig',
                                $emailData
                            );
                            if (!empty($emailData['images'])) {
                                foreach ($emailData['images'] as $imagePath) {
                                    $emailMessage->addAttachment($imagePath);
                                }
                            }

                            $this->messageBus->dispatch($emailMessage);
                        } catch (Throwable) {
                        }
                    }
                }
            }
        }

        if (!empty($errors)) {
            return new CarpetDesignOrderResponse($carpetDesignOrder, $errors);
        }
        $this->carpetDesignOrderRepository->flush();

        return new CarpetDesignOrderResponse($carpetDesignOrder);
    }

    private function canBeTransmitted(CarpetDesignOrder $cdo): array
    {
        $carpetSpecification = $cdo->getCarpetSpecification();
        $errors = [];

        if (empty($carpetSpecification->getDescription())) {
            $errors[] = 'Description is required.';
        }
        if (empty($carpetSpecification->getCollection())) {
            $errors[] = 'Collection is required.';
        }
        if (empty($carpetSpecification->getQuality())) {
            $errors[] = 'Quality is required.';
        }
        if (empty($carpetSpecification->getModel())) {
            $errors[] = 'Model is required.';
        }
        if (empty($carpetSpecification->getCarpetDimensions())) {
            $errors[] = 'Carpet dimensions are required.';
        }

        $sommeMatieres = $carpetSpecification->getMaterials()->reduce(
            fn($carry, $material) => $carry + $material->getRate(),
            0
        );

        if ($sommeMatieres < 100) {
            $errors[] = 'The total material rate must be at least 100.';
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors,
            ];
        }

        return ['success' => true];
    }

    private function validateFinishingCarpet(CarpetDesignOrder $cdo): bool
    {
        $imageTypes = array_column(
            $this->carpetDesignOrderRepository->getImageTypeNames($cdo->getId()),
            'name'
        );

        return in_array('Vignette', $imageTypes, true)
            && (in_array('Légende A4', $imageTypes, true) || in_array('Légende A3', $imageTypes, true));
    }


}
