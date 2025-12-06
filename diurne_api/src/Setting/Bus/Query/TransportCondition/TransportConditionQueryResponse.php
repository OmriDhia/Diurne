<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportCondition;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\TransportCondition;

class TransportConditionQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $transportConditions)
    {
    }

    public function toArray(): array
    {
        /* @var TransportCondition $transportCondition */
        return array_map(fn($transportCondition) => [
            'id' => $transportCondition->getId(),
            'name' => $transportCondition->getName(),
            'languages' => $transportCondition->getTransportConditionLangs()->map(fn($lang) => [
                'transport_condition_lang_id' => $lang->getId(),
                'label' => $lang->getLabel(),
                'description' => $lang->getDescription(),
                'lang_id' => $lang->getLanguage()->getId(),
            ])->toArray(),
        ], $this->transportConditions);
    }
}
