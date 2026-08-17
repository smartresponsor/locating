<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Http\Location\LocationAddressSuggestHttpServiceInterface;
use App\Locating\ServiceInterface\Http\Location\LocationAddressSuggestServiceInterface;
use App\Locating\ServiceInterface\Http\Location\LocationQuotaGuardInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class LocationAddressSuggestHttpService implements LocationAddressSuggestHttpServiceInterface
{
    public function __construct(
        private readonly LocationAddressSuggestServiceInterface $suggestService,
        private readonly ?LocationQuotaGuardInterface $quotaGuard = null,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string) $request->query->get('query', ''));
        $country = $request->query->get('country');
        $limit = (int) ((string) $request->query->get('limit', '5'));

        if ($limit <= 0) {
            $limit = 5;
        }

        if ($limit > 20) {
            $limit = 20;
        }

        if ('' === $query) {
            return new JsonResponse(['items' => []]);
        }

        if (null !== $this->quotaGuard && !$this->quotaGuard->isAllowed(LocationQuotaGuardInterface::OPERATION_SUGGEST)) {
            return new JsonResponse(['items' => [], 'quotaExceeded' => true]);
        }

        $items = array_map(
            static fn ($item): array => $item->toArray(),
            $this->suggestService->suggest($query, is_string($country) ? $country : null, $limit),
        );

        return new JsonResponse(['items' => $items]);
    }
}
