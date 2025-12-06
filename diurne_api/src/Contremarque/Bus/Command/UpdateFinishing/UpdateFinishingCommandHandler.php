<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateFinishing;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\Finishing;
use App\Contremarque\Repository\FinishingRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateFinishingCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FinishingRepository $finishingRepository
    ) {}

    public function __invoke(UpdateFinishingCommand $command): UpdateFinishingResponse
    {
        // Fetch the Finishing entity
        $finishing = $this->finishingRepository->find($command->id);

        if (!$finishing) {
            throw new ResourceNotFoundException();
        }

        // Check if the customer instruction ID matches
        if ($finishing->getCustomerInstruction()->getId() !== $command->customerInstructionId) {
            throw new ResourceNotFoundException();
        }

        // Update the Finishing entity
        if (null !== $command->fabricColor) {
            $finishing->setFabricColor($command->fabricColor);
        }
        if (null !== $command->fringe) {
            $finishing->setFringe($command->fringe);
        }
        if (null !== $command->withoutBanking) {
            $finishing->setWithoutBanking($command->withoutBanking);
        }
        if (null !== $command->noBinding) {
            $finishing->setNoBinding($command->noBinding);
        }
        if (null !== $command->mzCarved) {
            $finishing->setMzCarved($command->mzCarved);
        }
        if (null !== $command->otherCarvedSignature) {
            $finishing->setOtherCarvedSignature($command->otherCarvedSignature);
        }
        if (null !== $command->standardVelvetHeight) {
            $finishing->setStandardVelvetHeight($command->standardVelvetHeight);
        }
        if (null !== $command->specialVelvetHeight) {
            $finishing->setSpecialVelvetHeight($command->specialVelvetHeight);
        }

        // Persist the updated entity
        $this->entityManager->flush();

        // Return the response with the updated Finishing details
        return new UpdateFinishingResponse(
            id: $finishing->getId(),
            fabricColor: $finishing->getFabricColor(),
            fringe: $finishing->isFringe(),
            withoutBanking: $finishing->isWithoutBanking(),
            noBinding: $finishing->isNoBinding(),
            mzCarved: $finishing->isMzCarved(),
            otherCarvedSignature: $finishing->getOtherCarvedSignature(),
            standardVelvetHeight: $finishing->getStandardVelvetHeight(),
            specialVelvetHeight: $finishing->getSpecialVelvetHeight()
        );
    }
}
