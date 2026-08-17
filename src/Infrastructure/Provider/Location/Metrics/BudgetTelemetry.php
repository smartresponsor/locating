<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location\Metrics;

use App\Locating\InfrastructureInterface\Provider\Location\Metrics\BudgetTelemetryInterface;

final class BudgetTelemetry implements BudgetTelemetryInterface
{
    /** @param list<array{tenantId?:string,op?:string,remaining?:float|int,used?:float|int}> $row */
    public function export(array $row): string
    {
        $lines = [];
        $lines[] = '# HELP locator_budget_remaining Remaining budget amount';
        $lines[] = '# TYPE locator_budget_remaining gauge';

        foreach ($row as $r) {
            $tenantId = is_string($r['tenantId'] ?? null) ? $r['tenantId'] : 't';
            $op = is_string($r['op'] ?? null) ? $r['op'] : 'op';
            $t = $this->label($tenantId, $op);
            $remaining = (float) ($r['remaining'] ?? 0.0);
            $used = (float) ($r['used'] ?? 0.0);
            $lines[] = "locator_budget_remaining{$t} {$remaining}";
            $lines[] = '# HELP locator_budget_used Used budget amount';
            $lines[] = '# TYPE locator_budget_used gauge';
            $lines[] = "locator_budget_used{$t} {$used}";
        }

        return implode("\n", $lines) . "\n";
    }

    private function label(string $tenantId, string $op): string
    {
        $esc = fn (string $v): string => str_replace(['"', "\n"], ['\\"', ''], $v);

        return '{tenant="' . $esc($tenantId) . '",op="' . $esc($op) . '"}';
    }
}
