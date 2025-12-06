<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateCarpetOrder;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\CarpetOrder\CarpetOrder;
use App\Contremarque\Entity\CarpetOrder\CarpetOrderDetail;
use App\Contremarque\Entity\Quote;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Contremarque\Service\CheckSpecificationCoherence\CheckSpecificationCoherence;
use App\Contremarque\Service\QuoteCloner\QuoteCloner;
use App\Event\Entity\Event;
use App\Event\Repository\EventNomenclatureRepository;
use App\Event\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use DateTimeImmutable;

class CreateCarpetOrderHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private QuoteRepository             $quoteRepository,
        private ContremarqueRepository      $contremarqueRepository,
        private QuoteCloner                 $quoteCloner,
        private ImageCommandRepository      $imageCommandRepository,
        private CheckSpecificationCoherence $checkSpecificationCoherence,
        private EventRepository             $eventRepository,
        private EventNomenclatureRepository $eventNomenclatureRepository,
    )
    {
    }

    public function __invoke(CreateCarpetOrderCommand $command): CreateCarpetOrderResponse
    {
        // Verify original quote exists
        $originalQuote = $this->quoteRepository->find($command->originalQuoteId);
        if (!$originalQuote) {
            throw new EntityNotFoundException(
                sprintf('Original Quote with id %d not found', $command->originalQuoteId)
            );
        }
        foreach ($originalQuote->getQuoteDetails() as $quoteDetail) {
            $carpetDesignOrder = $quoteDetail->getCarpetDesignOrder();

            if (!$carpetDesignOrder) {
                throw new ValidationException([
                    sprintf('there is no image command assigned to this quote ref: %s', $quoteDetail->getReference())
                ]);
            }

            // Check image command
            $imageCommand = $this->imageCommandRepository->findOneBy(['carpetDesignOrder' => $carpetDesignOrder]);
            if (!$imageCommand) {
                throw new ValidationException([
                    sprintf('this carpetDesignOrder id: %s has no valid Image Command', $carpetDesignOrder->getId())
                ]);
            }

            // Check specification coherence
            $coherenceResponse = $this->checkSpecificationCoherence->check($carpetDesignOrder, $quoteDetail);

            if (!$coherenceResponse->isCoherent()) {
                throw new ValidationException([
                    sprintf(
                        'Les spécifications ne sont pas cohérentes pour la maquette %d et  devis détail %s',
                        $carpetDesignOrder->getId(),
                        $quoteDetail->getReference()
                    )
                ]);
            }
        }


        // Verify contremarque exists
        $contremarque = $this->contremarqueRepository->find($command->contremarqueId);
        if (!$contremarque) {
            throw new EntityNotFoundException(
                sprintf('Contremarque with id %d not found', $command->contremarqueId)
            );
        }

        // Clone the quote
        $clonedQuote = $this->quoteCloner->cloneQuoteForOrder($originalQuote);
        $this->entityManager->persist($clonedQuote);
        $this->entityManager->flush();
        // Create the carpet order
        $carpetOrder = new CarpetOrder();
        $carpetOrder->setReference($clonedQuote->getReference());
        $carpetOrder->setOriginalQuote($originalQuote);
        $carpetOrder->setClonedQuote($clonedQuote);
        $carpetOrder->setContremarqueId($contremarque);

        // Définir le commercialId de la commande
        $originalQuoteCommercialId = $originalQuote->getCommercialId();
        if ($originalQuoteCommercialId) {
            $carpetOrder->setCommercialId($originalQuoteCommercialId);
        } else {
            // Récupérer le commercial de la contremarque
            $contremarqueCommercialId = $contremarque->getCommercialId();
            if ($contremarqueCommercialId) {
                $carpetOrder->setCommercialId($contremarqueCommercialId);
            }
        }

        $carpetOrder->setCreatedAt($command->createdAt ?? new \DateTime());
        $carpetOrder->setUpdatedAt($command->createdAt ?? new \DateTime());
        // Mark the original quote as transformed into an order

        $originalQuote->setTransformedIntoAnOrder(true);
        $originalQuote->setValidatedAt(new DateTimeImmutable());
        $this->entityManager->persist($originalQuote);
        $this->entityManager->persist($carpetOrder);
        $this->entityManager->flush();

        $this->createOrderDetails($clonedQuote, $carpetOrder);

        $nomenclature = $this->eventNomenclatureRepository->findBySubject('Nouvelle Commande');

        $event = new Event();
        $event->setNomenclature($nomenclature);
        $event->setCustomer($contremarque->getCustomer());
        $event->setContremarque($contremarque);
        $event->setEventDate(new DateTimeImmutable());
        $event->setCommentaire('Commande : ' . $carpetOrder->getReference());
        $this->eventRepository->persist($event);
        $this->eventRepository->flush();

        return new CreateCarpetOrderResponse($carpetOrder);
    }

    private function createOrderDetails(Quote $clonedQuote, CarpetOrder $carpetOrder): void
    {
        $quoteDetails = $clonedQuote->getQuoteDetails();

        foreach ($quoteDetails as $quoteDetail) {
            $orderDetail = new CarpetOrderDetail();
            $orderDetail->setCarpetOrder($carpetOrder);
            $orderDetail->setQuoteDetailId($quoteDetail);
            $orderDetail->setCreatedAt(new \DateTime());
            $orderDetail->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($orderDetail);
        }

        $this->entityManager->flush();
    }

}