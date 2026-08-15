<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Http\Location\LocationGovernanceExplanationHttpServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class LocationGovernanceExplanationHttpService implements LocationGovernanceExplanationHttpServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceExplanationServiceInterface $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->report()->toArray());
    }
}
