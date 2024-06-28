<?php

namespace App\Enum;

enum ExperienceType: string
{
    case Internship   = 'Stage';
    case Apprenticeship = 'Alternance';
    case CDI = 'CDI';
    case CDD = 'CDD';
    case Freelance = 'Freelance';
    case PersonalProject = 'Projet personnel';
}
