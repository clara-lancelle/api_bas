<?php

namespace App\Controller;

use App\Enum\OfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class LastOffers extends AbstractController
{

    public function __construct(private OfferRepository $offerRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        $lastOffers = $this->offerRepository->getLastOffers();
        return new JsonResponse($lastOffers);
    }
}