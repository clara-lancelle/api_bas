<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Offer;
use App\Enum\OfferType;
use App\Repository\OfferRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;

final class OfferProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface $decorated, private OfferRepository $offerRepository )
    {

    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            // $offers = $this->offerRepository->getOffers($uriVariables['type']);
            $offers = $this->offerRepository->findBy(['type' => $uriVariables['type']]);
            return $offers;
        }
        return $this->offerRepository->find($uriVariables['id']);

    }
}
