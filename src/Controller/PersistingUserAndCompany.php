<?php

namespace App\Controller;

use App\Service\PersistingUserAndCompanyService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PersistingUserAndCompany
{
    public function __construct(private PersistingUserAndCompanyService $service)
    {   
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->service->createCompanyUserAndCompany($data);
    }
}