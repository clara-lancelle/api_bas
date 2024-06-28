<?php

namespace App\Controller;

use App\Enum\LanguageLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class LanguageLevels extends AbstractController
{

    public function __construct()
    {
    }

    public function __invoke(): object|array
    {
        return LanguageLevel::cases();
    }
}