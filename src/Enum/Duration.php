<?php

namespace App\Enum;

enum Duration: string
{
    case lessThan2months     = 'Moins de 2 mois';
    case between2and6months  = 'Entre 2 et 6 mois';
    case between6and12months = 'Entre 6 et 12 moins';
    case moreThan12months    = 'Plus de 12 mois';
}
