<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Controller\Http\Location;

use App\Locating\ControllerInterface\Http\Location\StatusControllerInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationStatusReportServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class StatusController implements StatusControllerInterface
{
    public function __construct(private readonly LocationStatusReportServiceInterface $statusReportService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->statusReportService->report()->toArray());
    }
}
