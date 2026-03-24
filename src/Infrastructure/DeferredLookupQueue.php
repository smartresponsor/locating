<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Infrastructure;
final class DeferredLookupQueue implements DeferredLookupQueueInterface {
    /** @var array<int, array{id:string,tenant:string,payload:array}> */
    private array $q = [];
    public function enqueue(string $tenantId, array $payload): string {
        $id = bin2hex(random_bytes(6));
        $this->q[] = ['id'=>$id,'tenant'=>$tenantId,'payload'=>$payload];
        return $id;
    }
    public function dequeue(): ?array {
        return array_shift($this->q) ?: null;
    }
}
