<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Controller;

use App\ControllerInterface\AddressSuggestControllerInterface;
use App\EntityInterface\AddressSuggestionInterface;
use App\Service\AddressQuotaGuard;
use App\ServiceInterface\AddressQuotaGuardInterface;
use App\ServiceInterface\AddressSuggestInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * HTTP endpoint for address suggestions.
 */
final class AddressSuggestController implements AddressSuggestControllerInterface
{
    private const DEFAULT_LIMIT = 5;
    private const MAX_LIMIT = 20;

    public function __construct(
        private AddressSuggestInterface $suggestService,
        private ?AddressQuotaGuardInterface $quotaGuard = null
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string)$request->query->get('query', ''));
        $country = $request->query->get('country');
        $limit = $this->normalizeLimit($request->query->get('limit'));

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

        return new JsonResponse([
            'items' => array_map($this->serializeSuggestion(...), $items),
        ]);
    }

    private function normalizeLimit(mixed $limit): int
    {
        $normalizedLimit = is_numeric($limit) ? (int) $limit : self::DEFAULT_LIMIT;

        if ($normalizedLimit <= 0) {
            return self::DEFAULT_LIMIT;
        }

        return min($normalizedLimit, self::MAX_LIMIT);
    }

    /**
     * @return array{
     *     label:string,
     *     address:array<string, string>,
     *     providerKey:?string,
     *     score:?float,
     *     rankReason:array<string, float>
     * }
     */
    private function serializeSuggestion(AddressSuggestionInterface $item): array
    {
        return [
            'label' => $item->label(),
            'address' => $item->addressData()->toComponentMap(),
            'providerKey' => $item->providerKey(),
            'score' => $item->score(),
            'rankReason' => $item->rankReason(),
        ];
    }
}
