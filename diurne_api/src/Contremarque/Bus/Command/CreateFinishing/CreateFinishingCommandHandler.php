<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateFinishing;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\Finishing;
use App\Contremarque\Repository\CustomerInstructionRepository;
use App\Contremarque\Repository\FinishingRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateFinishingCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CustomerInstructionRepository $customerInstructionRepository,
        private readonly FinishingRepository $finishingRepository
    ) {}

    public function __invoke(CreateFinishingCommand $command): CreateFinishingResponse
    {
        // Fetch the CustomerInstruction entity
        $customerInstruction = $this->customerInstructionRepository->find($command->customerInstructionId);

        if (!$customerInstruction) {
            throw new ResourceNotFoundException();
        }

        // Create a new Finishing entity
        $finishing = new Finishing();
        $finishing->setFabricColor($command->fabricColor);
        $finishing->setFringe($command->fringe);
        $finishing->setWithoutBanking($command->withoutBanking);
        $finishing->setNoBinding($command->noBinding);
        $finishing->setMzCarved($command->mzCarved);
        $finishing->setOtherCarvedSignature($command->otherCarvedSignature);
        $finishing->setStandardVelvetHeight($command->standardVelvetHeight);
        $finishing->setSpecialVelvetHeight($command->specialVelvetHeight);

        // Associate Finishing with CustomerInstruction
        $finishing->setCustomerInstruction($customerInstruction);

        // Persist and flush
        $this->entityManager->persist($finishing);
        $this->entityManager->flush();

        // Return a response
        return new CreateFinishingResponse(
            id: (string)$finishing->getId(),
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
