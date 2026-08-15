<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Http\Location\LocationMetricsHttpServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationMetricsExportServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LocationMetricsHttpService implements LocationMetricsHttpServiceInterface
{
    public function __construct(private readonly LocationMetricsExportServiceInterface $metricsExportService)
    {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->metricsExportService->exportPrometheus(), Response::HTTP_OK, ['Content-Type' => 'text/plain; version=0.0.4']);
    }
}
