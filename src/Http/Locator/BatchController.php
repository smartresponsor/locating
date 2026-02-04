<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Http\Locator;
use App\Layer\Locator\BatchService;
final class BatchController {
    public function __construct(private BatchService $service){}
    public function post(array $payload): array {
        $items = (array)($payload['items'] ?? []);
        $deadline = (int)($payload['deadline_ms'] ?? 1000);
        return $this->service->handle($items, $deadline);
    }
}
