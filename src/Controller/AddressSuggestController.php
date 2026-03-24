<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Controller;

use Smartresponsor\ControllerInterface\AddressSuggestControllerInterface;
use Smartresponsor\ServiceInterface\AddressSuggestInterface;
use Smartresponsor\ServiceInterface\AddressQuotaGuardInterface;
use Smartresponsor\Service\AddressQuotaGuard;
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
