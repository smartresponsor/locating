<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Http\Location\LocationAddressReverseHttpServiceInterface;
use App\Locating\ServiceInterface\Http\Location\LocationAddressReverseServiceInterface;
use App\Locating\ServiceInterface\Http\Location\LocationQuotaGuardInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class LocationAddressReverseHttpService implements LocationAddressReverseHttpServiceInterface
{
    public function __construct(
        private readonly LocationAddressReverseServiceInterface $reverseService,
        private readonly ?LocationQuotaGuardInterface $quotaGuard = null,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $latRaw = (string) $request->query->get('lat', '');
        $lonRaw = (string) $request->query->get('lon', '');
        $country = $request->query->get('country');

        if ('' === $latRaw || '' === $lonRaw) {
            return new JsonResponse(['error' => 'lat and lon query parameters are required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $latitude = (float) $latRaw;
        $longitude = (float) $lonRaw;

        if (!is_finite($latitude) || !is_finite($longitude) || $latitude < -90.0 || $latitude > 90.0 || $longitude < -180.0 || $longitude > 180.0) {
            return new JsonResponse(['error' => 'lat/lon are out of bounds'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (null !== $this->quotaGuard && !$this->quotaGuard->isAllowed(LocationQuotaGuardInterface::OPERATION_REVERSE)) {
            return new JsonResponse(['quotaExceeded' => true]);
        }

        $result = $this->reverseService->reverse($latitude, $longitude, is_string($country) ? $country : null);

        return new JsonResponse($result->toArray());
    }
}
