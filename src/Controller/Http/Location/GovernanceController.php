<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Controller\Http\Location;

use App\ControllerInterface\Http\Location\GovernanceControllerInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceReportServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceController implements GovernanceControllerInterface
{
    public function __construct(private readonly ProviderGovernanceReportServiceInterface $reportService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->reportService->report()->toArray());
    }
}
