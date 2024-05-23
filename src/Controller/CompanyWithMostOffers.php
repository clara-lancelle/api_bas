<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class CompanyWithMostOffers extends AbstractController
{

    public function __construct(private CompanyRepository $companyRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        $companiesWithTheMostOffers = $this->companyRepository->getCompaniesWithTheMostOffers();
        return new JsonResponse($companiesWithTheMostOffers);
    }
}