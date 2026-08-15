<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Controller\Http\Location;

use App\Locating\ControllerInterface\Http\Location\GovernanceRemediationPlanControllerInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceRemediationPlanServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceRemediationPlanController implements GovernanceRemediationPlanControllerInterface
{
    public function __construct(private readonly LocationProviderGovernanceRemediationPlanServiceInterface $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->report()->toArray());
    }
}
