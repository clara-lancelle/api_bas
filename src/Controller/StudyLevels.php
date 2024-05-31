<?php

namespace App\Controller;

use App\Enum\StudyLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class StudyLevels extends AbstractController
{

    public function __construct()
    {
    }

    public function __invoke(): object|array
    {
        return StudyLevel::cases();
    }
}