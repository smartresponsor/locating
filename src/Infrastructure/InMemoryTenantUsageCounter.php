<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Infrastructure;

use Smartresponsor\InfrastructureInterface\TenantUsageCounterInterface;

/**
 * Naive in-memory usage counter with a simple 60 second window.
 * This implementation is process-local and can be replaced with Redis or database backed counter.
 */
final class InMemoryTenantUsageCounter implements TenantUsageCounterInterface
{
    /**
     * @var array<string,array{windowStart:int,count:int}>
     */
    private array $state = [];

    public function increment(string $tenantId, string $operation, int $limitPerMinute): bool
    {
        if ($limitPerMinute <= 0) {
            return false;
        }

        $key = $tenantId . ':' . $operation;
        $now = time();

        $window = $this->state[$key] ?? ['windowStart' => $now, 'count' => 0];

        if ($now - $window['windowStart'] >= 60) {
            $window['windowStart'] = $now;
            $window['count'] = 0;
        }

        if ($window['count'] >= $limitPerMinute) {
            $this->state[$key] = $window;

            return false;
        }

        $window['count']++;
        $this->state[$key] = $window;

        return true;
    }
}
