<?php

namespace App\Setting\Bus\Command\TransportCondition;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Bus\Command\TransportConditionLang\TransportConditionLangResponse;
use App\Setting\Entity\TransportCondition;

class TransportConditionResponse implements CommandResponse
{
    private array $languages = [];

    public function __construct(private readonly TransportCondition $transportCondition)
    {
    }

    public function toArray(): array
    {
        $languages = $this->transportCondition->getTransportConditionLangs()->map(fn($lang) => [
            'transport_condition_lang_id' => $lang->getId(),
            'label' => $lang->getLabel(),
            'description' => $lang->getDescription(),
            'lang_id' => $lang->getId(),
        ])->toArray();

        $languages = array_merge($languages, $this->languages);

        return [
            'id' => $this->transportCondition->getId(),
            'name' => $this->transportCondition->getName(),
            'languages' => $languages,
        ];
    }

    public function getTransportConditionId(): int|null
    {
        return $this->transportCondition->getId();
    }

    public function addLanguage(TransportConditionLangResponse $transportConditionLangResponse): void
    {
        $this->languages[] = $transportConditionLangResponse->toArray();
    }
}
