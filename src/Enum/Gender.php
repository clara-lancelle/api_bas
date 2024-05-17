<?php

namespace App\Enum;

enum Gender: string
{
    case Male   = 'Homme';
    case Female = 'Femme';
    case Other  = 'Autre';
}
