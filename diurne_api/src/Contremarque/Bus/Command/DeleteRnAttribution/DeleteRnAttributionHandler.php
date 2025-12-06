<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteRnAttribution;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;
use App\Contremarque\Repository\RnAttributionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteRnAttributionHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface  $entityManager,
        private RnAttributionRepository $rnAttributionRepository
    )
    {
    }

    public function __invoke(DeleteRnAttributionCommand $command): DeleteRnAttributionResponse
    {
        $rnAttribution = $this->rnAttributionRepository->find($command->id);

        if (!$rnAttribution) {
            throw new ResourceNotFoundException(
                sprintf('RnAttribution with id %d not found', $command->id)
            );
        }

        if ($quoteDetail = $rnAttribution->getCarpetOrderDetail()?->getQuoteDetail()) {
            $quoteDetail->setRn(null);
            $this->entityManager->persist($quoteDetail);
        }

        $this->entityManager->remove($rnAttribution);
        $this->entityManager->flush();
        return new DeleteRnAttributionResponse($command->id);
    }
}