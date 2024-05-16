<?php

namespace App\Entity;

enum Gender: string
{
    case Male   = 'Homme';
    case Female = 'Femme';
    case Other  = 'Autre';
}
