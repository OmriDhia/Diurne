<?php

namespace App\Setting\Bus\Command\TransportConditionLang;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\TransportConditionLang;

class TransportConditionLangResponse implements CommandResponse
{
    public function __construct(private readonly TransportConditionLang $transportConditionLang)
    {
    }

    public function toArray(): array
    {
        return [
            'transport_condition_lang_id' => $this->transportConditionLang->getId(),
            'label' => $this->transportConditionLang->getLabel(),
            'description' => $this->transportConditionLang->getDescription(),
            'lang_id' => $this->transportConditionLang->getLanguage()->getId(),
        ];
    }
}
