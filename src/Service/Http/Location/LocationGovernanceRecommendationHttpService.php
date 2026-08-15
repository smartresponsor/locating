<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Http\Location\LocationGovernanceRecommendationHttpServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceRecommendationServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class LocationGovernanceRecommendationHttpService implements LocationGovernanceRecommendationHttpServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceRecommendationServiceInterface $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->report()->toArray());
    }
}
