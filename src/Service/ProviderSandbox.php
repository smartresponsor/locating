<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class ProviderSandbox {
    /** @var array<string, ProviderAdapterInterface> */
    private array $adapter = [];
    /** Register adapter under provider id */
    public function register(string $providerId, ProviderAdapterInterface $adapter): void { $this->adapter[$providerId] = $adapter; }
    /** Route call to a specific provider id */
    public function route(string $providerId, array $request): array {
        if (!isset($this->adapter[$providerId])) { return ['status'=>'error','error'=>'provider_not_found']; }
        $res = $this->adapter[$providerId]->call($request);
        $res['_provider'] = $providerId;
        return $res;
    }
}
