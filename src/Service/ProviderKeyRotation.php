<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class ProviderKeyRotation implements ProviderKeyRotationInterface {
    /** @var array<string, array<string, array<string, string>>> map[provider][tenant][region] => version */
    private array $v = [];
    public function active(string $providerId, string $tenantId, string $region): string {
        return $this->v[$providerId][$tenantId][$region] ?? 'v1';
    }
    public function rotate(string $providerId, string $tenantId, string $region): string {
        $cur = $this->active($providerId,$tenantId,$region);
        $n = (int)substr($cur,1) + 1;
        $ver = 'v'.$n;
        $this->v[$providerId] = $this->v[$providerId] ?? [];
        $this->v[$providerId][$tenantId] = $this->v[$providerId][$tenantId] ?? [];
        $this->v[$providerId][$tenantId][$region] = $ver;
        return $ver;
    }
}
