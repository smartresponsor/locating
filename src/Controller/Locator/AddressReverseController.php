<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Controller\Locator;

use Smartresponsor\ControllerInterface\Locator\AddressReverseControllerInterface;
use Smartresponsor\ServiceInterface\Locator\AddressReverseInterface;
use Smartresponsor\ServiceInterface\Locator\AddressQuotaGuardInterface;
use Smartresponsor\Service\Locator\AddressQuotaGuard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * HTTP endpoint for reverse geocoding.
 */
final class AddressReverseController implements AddressReverseControllerInterface
{
    public function __construct(
        private AddressReverseInterface $reverseService,
        private ?AddressQuotaGuardInterface $quotaGuard = null
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $latRaw = (string)$request->query->get('lat', '');
        $lonRaw = (string)$request->query->get('lon', '');
        $country = $request->query->get('country');

        if ($latRaw === '' || $lonRaw === '') {
            return new JsonResponse(
                ['error' => 'lat and lon query parameters are required'],
                400
            );
        }

        $latitude = (float)$latRaw;
        $longitude = (float)$lonRaw;

        if (!is_finite($latitude) || !is_finite($longitude) ||
            $latitude < -90.0 || $latitude > 90.0 ||
            $longitude < -180.0 || $longitude > 180.0
        ) {
            return new JsonResponse(
                ['error' => 'lat/lon are out of bounds'],
                400
            );
        }

        if ($this->quotaGuard !== null) {
            $allowed = $this->quotaGuard->isAllowed(AddressQuotaGuard::OPERATION_REVERSE);
            if (!$allowed) {
                return new JsonResponse(
                    ['quotaExceeded' => true],
                    200
                );
            }
        }

        $result = $this->reverseService->reverse(
            $latitude,
            $longitude,
            is_string($country) ? $country : null
        );

        return new JsonResponse($result->toArray());
    }
}
