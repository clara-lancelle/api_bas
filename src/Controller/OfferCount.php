<?php

namespace App\Controller;

use App\Enum\OfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class OfferCount extends AbstractController
{

    public function __construct(private OfferRepository $offerRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        $offerCount          = $this->offerRepository->count(['type' => OfferType::Internship]);
        $apprenticeshipCount = $this->offerRepository->count(['type' => OfferType::Apprenticeship]);
        return new JsonResponse(['internship' => $offerCount, 'apprenticeship' => $apprenticeshipCount]);
    }
}