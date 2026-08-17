<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Credential;

use App\Locating\ServiceInterface\Provider\Location\Credential\KeyStoreInterface;

final class KeyStore implements KeyStoreInterface
{
    /** @var array<string,array{key:string,start:?int,revoke:?int}> */
    private array $map = [];

    public function get(string $providerId): ?string
    {
        $row = $this->map[$providerId] ?? null;
        if (null === $row) {
            return null;
        }
        $now = time();
        if (($row['start'] && $now < $row['start']) || ($row['revoke'] && $now >= $row['revoke'])) {
            return null;
        }

        return $row['key'];
    }

    public function set(string $providerId, string $key, ?int $startAtTs = null, ?int $revokeAtTs = null): void
    {
        $this->map[$providerId] = ['key' => $key, 'start' => $startAtTs, 'revoke' => $revokeAtTs];
    }
}
