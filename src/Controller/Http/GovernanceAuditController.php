<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Controller\Http\Location;

use App\ControllerInterface\Http\Location\GovernanceAuditControllerInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceAuditServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceAuditController implements GovernanceAuditControllerInterface
{
    public function __construct(private readonly LocationProviderGovernanceAuditServiceInterface $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->report()->toArray());
    }
}
