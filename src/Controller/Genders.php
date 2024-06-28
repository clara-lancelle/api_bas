<?php

namespace App\Controller;

use App\Enum\Gender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class Genders extends AbstractController
{

    public function __construct()
    {
    }

    public function __invoke(): object|array
    {
        return Gender::cases();
    }
}