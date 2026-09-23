<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Privacy\Location;

use App\Locating\ServiceInterface\Privacy\Location\RetentionPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Privacy\RetentionInterface;

class RetentionPolicy implements RetentionInterface, RetentionPolicyInterface
{
    /** @var array<string,int> */
    private array $ttlOverride = [];

    /** @return array<string,array{ttl_days:int,action:string}> */
    public function policy(): array
    {
        return [
            'address' => ['ttl_days' => 365, 'action' => 'anonymize'],
            'audit' => ['ttl_days' => 1825, 'action' => 'retain'],
        ];
    }

    /** @return array{ttl_days:int,action:string} */
    public function rule(string $kind): array
    {
        $p = $this->policy();

        return $p[$kind] ?? ['ttl_days' => 365, 'action' => 'anonymize'];
    }

    public function set(string $entity, int $ttlDay): void
    {
        $this->ttlOverride[$entity] = max(0, $ttlDay);
    }

    public function ttl(string $entity): int
    {
        return $this->ttlOverride[$entity] ?? $this->rule($entity)['ttl_days'];
    }
}
