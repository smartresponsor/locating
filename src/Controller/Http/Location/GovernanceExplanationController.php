<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Controller\Http\Location;

use App\Locating\ControllerInterface\Http\Location\GovernanceExplanationControllerInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceExplanationController implements GovernanceExplanationControllerInterface
{
    public function __construct(private readonly LocationProviderGovernanceExplanationServiceInterface $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->report()->toArray());
    }
}
