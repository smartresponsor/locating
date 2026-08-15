<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Metrics\Health;

final class CompositeHealthCheck implements HealthCheckInterface
{
    /** @param list<HealthCheckInterface> $checks */
    public function __construct(private array $checks)
    {
    }

    public function nameEntity(): string
    {
        return 'composite';
    }

    public function check(): array
    {
        $result = ['status' => 'UP', 'details' => []];
        foreach ($this->checks as $chk) {
            $r = $chk->check();
            $result['details'][$chk->nameEntity()] = $r;
            if (($r['status'] ?? 'DOWN') !== 'UP') {
                $result['status'] = 'DOWN';
            }
        }

        return $result;
    }
}
