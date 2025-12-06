<?php

namespace App\Contremarque\Enum;

enum TransportConditionEnum: string
{
    case TRANSPORT_QUOTE_FR = 'Tranport quoté sur le devis (Fr)';
    case TRANSPORT_QUOTE_EN = 'Transport quoté sur le devis (EN)';
}
