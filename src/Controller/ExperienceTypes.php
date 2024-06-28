<?php

namespace App\Controller;

use App\Enum\ExperienceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ExperienceTypes extends AbstractController
{

    public function __construct()
    {
    }

    public function __invoke(): object|array
    {
        return ExperienceType::cases();
    }
}