<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Controller\Locator;

use App\ControllerInterface\Locator\AddressSuggestControllerInterface;
use App\ServiceInterface\Locator\AddressSuggestInterface;
use App\ServiceInterface\Locator\AddressQuotaGuardInterface;
use App\Service\Locator\AddressQuotaGuard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * HTTP endpoint for address suggestions.
 */
final class AddressSuggestController implements AddressSuggestControllerInterface
{
    public function __construct(
        private AddressSuggestInterface $suggestService,
        private ?AddressQuotaGuardInterface $quotaGuard = null
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string)$request->query->get('query', ''));
        $country = $request->query->get('country');
        $limitValue = (string)$request->query->get('limit', '5');
        $limit = (int)$limitValue;
        if ($limit <= 0) {
            $limit = 5;
        }
        if ($limit > 20) {
            $limit = 20;
        }

        if ($query === '') {
            return new JsonResponse(['items' => []]);
        }

        if ($this->quotaGuard !== null) {
            $allowed = $this->quotaGuard->isAllowed(AddressQuotaGuard::OPERATION_SUGGEST);
            if (!$allowed) {
                return new JsonResponse(['items' => [], 'quotaExceeded' => true], 200);
            }
        }

        $items = $this->suggestService->suggest($query, is_string($country) ? $country : null, $limit);

        $result = [];
        foreach ($items as $item) {
            $result[] = $item->toArray();
        }

        return new JsonResponse(['items' => $result]);
    }
}
