<?php

namespace App\Entity;

enum OfferType: string
{
    case Internship     = 'Stage';
    case Apprenticeship = 'Alternance';
}
