<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Controller\Http\Location;

use App\ControllerInterface\Http\Location\GovernanceRecommendationControllerInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceRecommendationServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceRecommendationController implements GovernanceRecommendationControllerInterface
{
    public function __construct(private readonly LocationProviderGovernanceRecommendationServiceInterface $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->report()->toArray());
    }
}
