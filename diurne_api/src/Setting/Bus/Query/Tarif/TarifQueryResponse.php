<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Tarif;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Tarif;

class TarifQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $tarifs)
    {
    }

    public function toArray(): array
    {
        /* @var Tarif $tarif */
        return array_map(fn($tarif) => [
            'id' => $tarif->getId(),
            'base_price' => $tarif->getBasePrice(),
            'label' => $tarif->getLabel(),
            'is_confidential' => $tarif->isConfidential(),
            'base_price_percentage' => $tarif->getBasePricePercentage(),
            'variation' => $tarif->getVariation(),
            'vat' => $tarif->getVat(),
            'tarif_base' => $tarif->isTarifBase(),
            'tarif_pro' => $tarif->isTarifPro(),
            'tarifGroup' => $tarif->getTarifGroup()?->getId(),
            'tarifTexture' => $tarif->getTarifTexture()?->getId(),
            'discountRule' => $tarif->getDiscountRule()?->getId(),
        ], $this->tarifs);
    }
}
