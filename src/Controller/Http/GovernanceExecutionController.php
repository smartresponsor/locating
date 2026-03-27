<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Controller\Http\Location;

use App\ControllerInterface\Http\Location\GovernanceExecutionControllerInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceExecutionServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceExecutionController implements GovernanceExecutionControllerInterface
{
    public function __construct(private readonly LocationProviderGovernanceExecutionServiceInterface $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->report()->toArray());
    }
}
