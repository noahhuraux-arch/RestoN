<?php

namespace App\Enum;

enum EnumEtatCommande: string
{
    case WaitingTreatment = 'Commande en attente de traitement';
    case InTreatment = 'Commande en cours de traitement';
    case Ready = 'Commande prête';
}
