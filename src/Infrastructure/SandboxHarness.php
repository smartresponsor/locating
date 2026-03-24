<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Infrastructure;
final class SandboxHarness implements SandboxHarnessInterface {
    /** @var array<string, array<string, array>> */
    private array $rec = [];
    public function record(string $providerId, string $sig, array $response): void {
        $this->rec[$providerId] = $this->rec[$providerId] ?? [];
        $this->rec[$providerId][$sig] = $response;
    }
    public function replay(string $providerId, string $sig): ?array {
        return $this->rec[$providerId][$sig] ?? null;
    }
    public function clear(string $providerId, string $sig): void {
        unset($this->rec[$providerId][$sig]);
    }
}
