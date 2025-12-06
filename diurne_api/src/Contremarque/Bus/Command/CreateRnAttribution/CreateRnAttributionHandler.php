<?php

namespace App\Contremarque\Bus\Command\CreateRnAttribution;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\CarpetOrder\CarpetOrderDetail;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;
use App\Contremarque\Entity\QuoteDetail;
use App\Workshop\Entity\Carpet;
use Doctrine\ORM\EntityManagerInterface;

class CreateRnAttributionHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @param CreateRnAttributionCommand $command
     * @return CreateRnAttributionResponse
     * @throws ResourceNotFoundException
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function __invoke(CreateRnAttributionCommand $command): CreateRnAttributionResponse
    {
        $carpetOrderDetail = $this->entityManager->getReference(
            CarpetOrderDetail::class,
            $command->carpetOrderDetailId
        );
        $carpet = $this->entityManager->getReference(
            Carpet::class,
            $command->carpetId
        );
        if (!$carpetOrderDetail) {
            throw new ResourceNotFoundException('CarpetOrderDetail not found');
        }
        if (!$carpet) {
            throw new ResourceNotFoundException('Carpet not found');
        }

        // Link the carpet with its order detail
        $carpet->setCarpetOrderDetail($carpetOrderDetail);
        $this->entityManager->persist($carpet);

        $rnAttribution = new RnAttribution();
        $rnAttribution->setCarpetOrderDetail($carpetOrderDetail);
        $rnAttribution->setCarpet($carpet);
        $rnValue = $carpet->getRnNumber();
        $rnAttribution->setRn($rnValue);
        $rnAttribution->setAttributedAt(new \DateTime($command->attributedAt));
        $this->entityManager->persist($rnAttribution);

        if ($quoteDetail = $carpetOrderDetail->getQuoteDetail()) {
            $quoteDetail->setRn($rnValue);
            $this->entityManager->persist($quoteDetail);
        }

        $this->entityManager->flush();

        return new CreateRnAttributionResponse($rnAttribution);
    }
}
