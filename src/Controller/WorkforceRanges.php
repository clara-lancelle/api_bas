<?php

namespace App\Controller;

use App\Enum\WorkforceRange;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class WorkforceRanges extends AbstractController
{

    public function __construct()
    {
    }

    public function __invoke(): object|array
    {
        return WorkforceRange::cases();
    }
}