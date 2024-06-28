<?php

namespace App\Controller;

use App\Enum\LanguageName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class LanguageNames extends AbstractController
{

    public function __construct()
    {
    }

    public function __invoke(): object|array
    {
        return LanguageName::cases();
    }
}