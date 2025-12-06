<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\AttachCarpetDesignOrder;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Contremarque\Service\ImageProvider;

class AttachCarpetDesignOrderCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly QuoteDetailRepository $quoteDetailRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ImageProvider $imageProvider
    ) {}

    public function __invoke(AttachCarpetDesignOrderCommand $command): AttachCarpetDesignOrderResponse
    {
        // Fetch CarpetDesignOrder
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->carpetDesignOrderId)
            ?? throw new InvalidArgumentException('CarpetDesignOrder not found.');

        // Fetch QuoteDetail
        $quoteDetail = $this->quoteDetailRepository->find($command->quoteDetailId)
            ?? throw new InvalidArgumentException('QuoteDetail not found.');

        // Attach CarpetDesignOrder to QuoteDetail
        $quoteDetail->setCarpetDesignOrder($carpetDesignOrder);

        // Persist the changes
        $this->entityManager->persist($quoteDetail);
        $this->entityManager->flush();

        // Return response
        return new AttachCarpetDesignOrderResponse($quoteDetail, $this->imageProvider);;
    }
}
