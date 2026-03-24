<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Http;
use Smartresponsor\Layer\BatchService;
final class BatchController {
    public function __construct(private BatchService $service){}
    public function post(array $payload): array {
        $items = (array)($payload['items'] ?? []);
        $deadline = (int)($payload['deadline_ms'] ?? 1000);
        return $this->service->handle($items, $deadline);
    }
}
