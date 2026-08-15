<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Credential;

use App\Locating\ServiceInterface\Provider\Location\Credential\KeyRotatorInterface;

final class KeyRotator implements KeyRotatorInterface
{
    /** @var array<string, array<int, array{key:string,start:int,revoke:int}>> */
    private array $map = [];
    public function register(string $providerId, string $key, int $startAtTs, int $revokeAtTs): void
    {
        $this->map[$providerId] = $this->map[$providerId] ?? [];
        $this->map[$providerId][] = ['key' => $key,'start' => $startAtTs,'revoke' => $revokeAtTs];
        usort($this->map[$providerId], fn ($a, $b) => $a['start'] <=> $b['start']);
    }
    public function active(string $providerId, int $nowTs = null): ?string
    {
        $now = $nowTs ?? time();
        $rows = $this->map[$providerId] ?? [];
        foreach ($rows as $r) {
            if ($now >= $r['start'] && $now < $r['revoke']) {
                return $r['key'];
            }
        }
        return null;
    }
}
