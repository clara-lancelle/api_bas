<?php

namespace App\Entity;

enum Gender: string
{
    case MALE   = 'Homme';
    case FEMALE = 'Femme';
    case OTHER  = 'Autre';
}
