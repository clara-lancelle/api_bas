<?php

namespace App\Controller;

use App\Enum\Duration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class Durations extends AbstractController
{

    public function __construct()
    {
    }

    public function __invoke(): object|array
    {
        return Duration::cases();
    }
}