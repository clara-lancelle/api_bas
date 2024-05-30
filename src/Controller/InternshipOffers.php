<?php

namespace App\Controller;

use App\Enum\OfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class InternshipOffers extends AbstractController
{

    public function __construct(private OfferRepository $offerRepository)
    {
    }

    // #[Route('/tutu')]
    public function __invoke(): JsonResponse
    {
        $internshipOffers = $this->offerRepository->getOffers(OfferType::Internship);
        return new JsonResponse($internshipOffers);
    }
}