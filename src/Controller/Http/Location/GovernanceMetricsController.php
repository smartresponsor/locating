<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Controller\Http\Location;

use App\ControllerInterface\Http\Location\GovernanceMetricsControllerInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceMetricsExportServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GovernanceMetricsController implements GovernanceMetricsControllerInterface
{
    public function __construct(private readonly ProviderGovernanceMetricsExportServiceInterface $exportService)
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
