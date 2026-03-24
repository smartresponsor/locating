<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Service;

use Smartresponsor\EntityInterface\AddressSuggestionInterface;
use Smartresponsor\InfrastructureInterface\AddressSuggestProviderInterface;
use Smartresponsor\ServiceInterface\AddressSuggestInterface;

/**
 * Address suggest service that delegates to one or more providers.
 */
final class AddressSuggest implements AddressSuggestInterface
{
    /**
     * @var AddressSuggestProviderInterface[]
     */
    private array $providers;

    /**
     * @param iterable<AddressSuggestProviderInterface> $providers
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
        if ($query === '') {
            return [];
        }

        $result = [];

        foreach ($this->providers as $provider) {
            $items = $provider->suggest($query, $countryCode, $limit);
            if ($items === []) {
                continue;
            }

            foreach ($items as $item) {
                $result[] = $item;
                if (count($result) >= $limit) {
                    return $result;
                }
            }
        }

        return $result;
    }
}
