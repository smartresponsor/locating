<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Http\Location\LocationGovernanceMetricsHttpServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceMetricsExportServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LocationGovernanceMetricsHttpService implements LocationGovernanceMetricsHttpServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceMetricsExportServiceInterface $exportService)
    {
    }

    public function __invoke(Request $request): Response
    {
        return new Response(
            $this->exportService->export()->toPrometheus(),
            Response::HTTP_OK,
            ['Content-Type' => 'text/plain; version=0.0.4'],
        );
    }
}
