<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetMaterial;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\CarpetMaterial;
use App\Contremarque\Repository\QuoteDetailRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteCarpetMaterialHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly QuoteDetailRepository  $quoteDetailRepository
    )
    {
    }

    public function __invoke(DeleteCarpetMaterialCommand $command): DeleteCarpetMaterialResponse
    {
        // Find the CarpetMaterial entity directly
        $carpetMaterial = $this->em->getRepository(CarpetMaterial::class)->find($command->getCarpetMaterialId());
        
        if (!$carpetMaterial) {
            throw new ResourceNotFoundException('Carpet material not found');
        }

        // Verify it belongs to the correct quote detail
        $carpetSpecification = $carpetMaterial->getCarpetSpecification();
        if (!$carpetSpecification) {
            throw new ResourceNotFoundException('Carpet specification not found for this material');
        }

        // Get the quote detail to verify ownership
        $quoteDetail = $this->quoteDetailRepository->find($command->getQuoteDetailId());
        if (!$quoteDetail) {
            throw new ResourceNotFoundException('Quote detail not found');
        }

        // Verify the material belongs to this quote detail's carpet specification
        if ($quoteDetail->getCarpetSpecification() !== $carpetSpecification) {
            throw new ResourceNotFoundException('Carpet material does not belong to this quote detail');
        }

        // Store the ID before removing the entity
        $materialId = $carpetMaterial->getId();

        $carpetSpecification->removeMaterial($carpetMaterial);
        $this->em->remove($carpetMaterial);
        $this->em->flush();

        return new DeleteCarpetMaterialResponse($materialId);
    }
}
