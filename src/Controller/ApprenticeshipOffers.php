<?php

namespace App\Controller;

use App\Enum\OfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ApprenticeshipOffers extends AbstractController
{

    public function __construct(private OfferRepository $offerRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        $apprenticeshipOffers = $this->offerRepository->getOffers(OfferType::Apprenticeship);
        return new JsonResponse($apprenticeshipOffers);
    }
}