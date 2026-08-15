<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\Credential\ProviderKeyVaultInterface;

final class ProviderKeyVaultService implements ProviderKeyVaultInterface
{
    /** @var array<string, array<int, array{keyId:string, secret:string, tsStart:int, tsEnd:int}>> */
    private array $map = [];

    public function add(string $providerId, string $keyId, string $secret, int $priority, int $tsStart, int $tsEnd): void
    {
        $p = &$this->map[$providerId];
        if (!isset($p)) {
            $p = [];
        }
        $p[$priority] = ['keyId' => $keyId, 'secret' => $secret, 'tsStart' => $tsStart, 'tsEnd' => $tsEnd];
    }

    public function current(string $providerId): ?string
    {
        $now = time();
        $p = $this->map[$providerId] ?? [];
        krsort($p, \SORT_NUMERIC);
        foreach ($p as $prio => $r) {
            if ($now >= $r['tsStart'] && $now <= $r['tsEnd']) {
                return $r['keyId'];
            }
        }

        return null;
    }

    public function rotate(string $providerId): ?string
    {
        $now = time();
        $p = $this->map[$providerId] ?? [];
        if (empty($p)) {
            return null;
        }
        krsort($p, \SORT_NUMERIC);
        array_shift($p);
        foreach ($p as $prio => $r) {
            if ($now >= $r['tsStart'] && $now <= $r['tsEnd']) {
                return $r['keyId'];
            }
        }

        return null;
    }
}
