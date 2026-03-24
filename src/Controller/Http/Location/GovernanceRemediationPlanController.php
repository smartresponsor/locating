<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Controller\Http\Location;

use App\ControllerInterface\Http\Location\GovernanceRemediationPlanControllerInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceRemediationPlanServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceRemediationPlanController implements GovernanceRemediationPlanControllerInterface
{
    public function __construct(private readonly ProviderGovernanceRemediationPlanServiceInterface $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->report()->toArray());
    }
}
