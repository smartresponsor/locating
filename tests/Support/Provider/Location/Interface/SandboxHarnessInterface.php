<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 *  Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Tests\Support\Provider\Location\Interface;

interface SandboxHarnessInterface
{
    /**
     * Record provider response for a request signature.
     *
     * @param array<string,mixed> $response
     */
    public function record(string $providerId, string $sig, array $response): void;

    /** @return array<string,mixed>|null */
    public function replay(string $providerId, string $sig): ?array;
    /** Remove recorded entry. */
    public function clear(string $providerId, string $sig): void;
}
