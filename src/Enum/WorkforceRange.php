<?php

namespace App\Enum;

enum WorkforceRange: string
{
    case lessThanTen   = '1-9';
    case TenToFifty = '10-49';
    case FiftyToHundred  = '50-99';
    case HundredToTwoHundred  = '100-249';
    case TwoHundredToThousand  = '250-999';
    case moreThanThousand  = '1000 et supérieur';

}
