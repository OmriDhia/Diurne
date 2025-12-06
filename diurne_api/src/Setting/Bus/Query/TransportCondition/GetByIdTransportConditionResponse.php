<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportCondition;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\TransportCondition;

final readonly class GetByIdTransportConditionResponse implements QueryResponse
{
    public function __construct(private ?TransportCondition $transportCondition)
    {
    }

    public function toArray(): array
    {
        return $this->transportCondition ? [
            'id' => $this->transportCondition->getId(),
            'name' => $this->transportCondition->getName(),
            'languages' => $this->transportCondition->getTransportConditionLangs()->map(fn($lang) => [
                'transport_condition_lang_id' => $lang->getId(),
                'label' => $lang->getLabel(),
                'description' => $lang->getDescription(),
                'lang_id' => $lang->getId(),
            ])->toArray(),
        ] : [];
    }
}
