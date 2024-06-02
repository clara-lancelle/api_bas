<?php

namespace App\Controller;

use App\Enum\OfferType;
use App\Repository\OfferRepository;
use App\Repository\RequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class LastRequest extends AbstractController
{

    public function __construct(private RequestRepository $requestRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        $lastRequests          = $this->requestRepository->getLastRequests();
        return new JsonResponse($lastRequests);
    }
}