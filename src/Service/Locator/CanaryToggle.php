<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\CanaryToggleLegacyInterface;

final class CanaryToggle implements CanaryToggleLegacyInterface
{
    /** @var array<string, array<string,int>> flag => tenant => percent */
    private array $pct = [];

    public function isEnabled(string $flag, string $tenantId, ?string $userId = null): bool
    {
        $p = $this->pct[$flag][$tenantId] ?? 0;
        if ($p <= 0) {
            return false;
        }
        if ($p >= 100) {
            return true;
        }
        $uid = $userId ?? 'anon';
        $h = hexdec(substr(hash('sha1', $tenantId.'|'.$uid.'|'.$flag), 0, 6)) % 100;

        return $h < $p;
    }

    public function enablePercent(string $flag, string $tenantId, int $percent): void
    {
        $this->pct[$flag] = $this->pct[$flag] ?? [];
        $this->pct[$flag][$tenantId] = max(0, min(100, $percent));
    }

    public function disable(string $flag, string $tenantId): void
    {
        if (isset($this->pct[$flag])) {
            unset($this->pct[$flag][$tenantId]);
        }
    }
}
