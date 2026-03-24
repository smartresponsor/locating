<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Entity\Location\AddressSuggestionLegacyInterface;
use App\Bridge\Legacy\Provider\Location\AddressSuggestLegacyProviderInterface;
use App\Bridge\Legacy\Service\Location\AddressSuggestLegacyServiceInterface;

/**
 * Address suggest service that delegates to one or more providers.
 */
final class AddressSuggest implements AddressSuggestLegacyServiceInterface
{
    /**
     * @var AddressSuggestLegacyProviderInterface[]
     */
    private array $providers;

    /**
     * @param iterable<AddressSuggestLegacyProviderInterface> $providers
     */
    public function __construct(iterable $providers)
    {
        $this->providers = [];
        foreach ($providers as $provider) {
            $this->providers[] = $provider;
        }
    }

    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
    {
        if ('' === $query) {
            return [];
        }

        $result = [];

        foreach ($this->providers as $provider) {
            $items = $provider->suggest($query, $countryCode, $limit);
            if ([] === $items) {
                continue;
            }

            foreach ($items as $item) {
                if (!$item instanceof AddressSuggestionLegacyInterface) {
                    continue;
                }
                $result[] = $item;
                if (count($result) >= $limit) {
                    return $result;
                }
            }
        }

        return $result;
    }
}
