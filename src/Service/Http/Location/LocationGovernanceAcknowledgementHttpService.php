<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Http\Location\LocationGovernanceAcknowledgementHttpServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceAcknowledgementServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class LocationGovernanceAcknowledgementHttpService implements LocationGovernanceAcknowledgementHttpServiceInterface
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
        $normalized = [];
        foreach ($payload as $key => $value) {
            if (is_string($key)) {
                $normalized[$key] = $value;
            }
        }

        return new JsonResponse($this->service->acknowledge($normalized)->toArray());
    }
}
