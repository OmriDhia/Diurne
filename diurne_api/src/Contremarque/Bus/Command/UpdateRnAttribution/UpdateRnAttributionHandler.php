<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateRnAttribution;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\RnAttributionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Contremarque\Entity\QuoteDetail;
use DateTime;

class UpdateRnAttributionHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param RnAttributionRepository $rnAttributionRepository
     */
    public function __construct(
        private EntityManagerInterface  $entityManager,
        private RnAttributionRepository $rnAttributionRepository
    )
    {
    }

    /**
     * @param UpdateRnAttributionCommand $command
     * @return UpdateRnAttributionResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(UpdateRnAttributionCommand $command): UpdateRnAttributionResponse
    {
        $rnAttribution = $this->rnAttributionRepository->find($command->id);
        if (!$rnAttribution) {
            throw new ResourceNotFoundException(
                sprintf('RnAttribution with id %d not found', $command->id)
            );
        }

        if ($command->rn !== null) {
            $rnAttribution->setRn($command->rn);
        }

        if ($command->attributedAt !== null) {
            $rnAttribution->setAttributedAt(new DateTime($command->attributedAt));
        }

        if ($command->canceledAt !== null) {
            $rnAttribution->setCanceledAt(new DateTime($command->canceledAt));
            // If an attribution is canceled, dissociate the carpet from its carpetOrderDetail
            $carpet = $rnAttribution->getCarpet();
            if ($carpet) {
                $carpet->setCarpetOrderDetail(null);
                $this->entityManager->persist($carpet);
            }
        }

        if ($quoteDetail = $rnAttribution->getCarpetOrderDetail()?->getQuoteDetail()) {
            $quoteDetail->setRn($rnAttribution->getRn());
            $this->entityManager->persist($quoteDetail);
        }

        $this->entityManager->persist($rnAttribution);
        $this->entityManager->flush();

        return new UpdateRnAttributionResponse($rnAttribution);
    }
}