<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateCarpetOrder;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetOrder\CarpetOrder;

class CreateCarpetOrderResponse implements CommandResponse
{
    /**
     * @param CarpetOrder $carpetOrder
     */
    public function __construct(
        private readonly CarpetOrder $carpetOrder
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->carpetOrder->getId(),
            'reference' => $this->carpetOrder->getReference(),
            'originalQuoteId' => $this->carpetOrder->getOriginalQuote()->getId(),
            'clonedQuoteId' => $this->carpetOrder->getClonedQuote(),
            'contremarqueId' => $this->carpetOrder->getContremarqueId()->getId(),
            'createdAt' => $this->carpetOrder->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $this->carpetOrder->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}