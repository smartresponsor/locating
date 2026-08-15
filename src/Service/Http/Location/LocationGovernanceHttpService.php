<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Http\Location\LocationGovernanceHttpServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceReportServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class LocationGovernanceHttpService implements LocationGovernanceHttpServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceReportServiceInterface $reportService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->reportService->report()->toArray());
    }
}
