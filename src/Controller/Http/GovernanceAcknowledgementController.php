<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Controller\Http\Location;

use App\ControllerInterface\Http\Location\GovernanceAcknowledgementControllerInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceAcknowledgementServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceAcknowledgementController implements GovernanceAcknowledgementControllerInterface
{
    public function __construct(private readonly LocationProviderGovernanceAcknowledgementServiceInterface $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true);
        if (!is_array($payload)) {
            $payload = [];
        }

        return new JsonResponse($this->service->acknowledge($payload)->toArray());
    }
}
