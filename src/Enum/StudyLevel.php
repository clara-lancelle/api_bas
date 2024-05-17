<?php

namespace App\Enum;

enum StudyLevel: string
{
    case Level1 = 'CAP, BEP';
    case Level2 = 'Bac';
    case Level3 = 'BTS, DUT, BUT';
    case Level4 = 'Licence';
    case Level5 = 'Master, DEA, DESS';
}
