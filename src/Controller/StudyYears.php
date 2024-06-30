<?php

namespace App\Controller;

use App\Enum\StudyYear;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class StudyYears extends AbstractController
{

    public function __construct()
    {
    }

    public function __invoke(): object|array
    {
        return StudyYear::cases();
    }
}