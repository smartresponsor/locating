<?php

declare(strict_types=1);

namespace Smartresponsor\Integration\Locator\Health;

final class CompositeHealthCheck implements HealthCheckInterface
{
    /** @param list<HealthCheckInterface> $checks */
    public function __construct(private array $checks)
    {
    }

    public function name(): string
    {
        return 'composite';
    }

    public function check(): array
    {
        $result = ['status' => 'UP', 'details' => []];
        foreach ($this->checks as $chk) {
            $r = $chk->check();
            $result['details'][$chk->name()] = $r;
            if (($r['status'] ?? 'DOWN') !== 'UP') {
                $result['status'] = 'DOWN';
            }
        }

        return $result;
    }
}
