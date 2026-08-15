<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Infrastructure\Location\Tenant;

use App\Locating\Model\Location\Tenant\TenantLimit;
use App\Locating\ServiceInterface\Location\Tenant\TenantConfigRepositoryInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantLimitInterface;

/**
 * Simple array-based tenant config repository.
 * It can be replaced by a database or remote configuration source.
 */
final class ArrayTenantConfigRepository implements TenantConfigRepositoryInterface
{
    /**
     * @var array<string,TenantLimitInterface>
     */
    private array $limitByKey;

    /**
     * @param array<int,array{tenantId:string,operation:string,limitPerMinute:int}> $config
     */
    public function __construct(array $config = [])
    {
        $this->limitByKey = [];

        foreach ($config as $row) {
            $tenantId = $row['tenantId'];
            $operation = $row['operation'];
            $limit = $row['limitPerMinute'];

            $key = $this->key($tenantId, $operation);
            $this->limitByKey[$key] = new TenantLimit($tenantId, $operation, $limit);
        }
    }

    public function findLimit(string $tenantId, string $operation): ?TenantLimitInterface
    {
        $key = $this->key($tenantId, $operation);

        return $this->limitByKey[$key] ?? null;
    }

    private function key(string $tenantId, string $operation): string
    {
        return $tenantId.':'.$operation;
    }
}
