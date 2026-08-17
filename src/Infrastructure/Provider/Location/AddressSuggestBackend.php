<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Backend\AddressSuggestBackendInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionProviderInterface as AddressSuggestProviderInterface;

final class AddressSuggestBackend implements AddressSuggestBackendInterface
{
    /** @var AddressSuggestProviderInterface[] */
    private array $providers;

    /** @param iterable<AddressSuggestProviderInterface> $providers */
    public function __construct(iterable $providers)
    {
        $this->providers = [];
        foreach ($providers as $provider) {
            $this->providers[] = $provider;
        }
    }

    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
    {
        if ('' === $query || $limit <= 0) {
            return [];
        }

        $items = [];
        foreach ($this->providers as $provider) {
            foreach ($provider->suggest($query, $countryCode, $limit) as $suggestion) {
                $items[] = [
                    'label' => $suggestion->label(),
                    'address' => $suggestion->address()->toArray(),
                    'providerKey' => $suggestion->providerKey() ?? '',
                ];
                if (count($items) >= $limit) {
                    return $items;
                }
            }
        }

        return $items;
    }
}
