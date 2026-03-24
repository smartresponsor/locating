<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Entity\Locator;

use App\Bridge\Legacy\Provider\Location\ProviderAdapterLegacyInterface;

final class ProviderSandbox
{
    /** @var array<string,ProviderAdapterLegacyInterface> */
    private array $adapter = [];

    /** Register adapter under provider id */
    public function register(string $providerId, ProviderAdapterLegacyInterface $adapter): void
    {
        $this->adapter[$providerId] = $adapter;
    }

    /**
     * Route call to a specific provider id.
     *
     * @param array<string,mixed> $request
     *
     * @return array<string,mixed>
     */
    public function route(string $providerId, array $request): array
    {
        if (!isset($this->adapter[$providerId])) {
            return [
                'status' => 'error',
                'error' => 'provider_not_found',
            ];
        }

        $res = $this->adapter[$providerId]->call($request);
        $res['_provider'] = $providerId;

        return $res;
    }
}
