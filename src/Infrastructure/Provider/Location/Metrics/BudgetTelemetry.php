<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location\Metrics;

final class BudgetTelemetry implements BudgetTelemetryInterface
{
    public function export(array $row): string
    {
        $lines = [];
        $lines[] = '# HELP locator_budget_remaining Remaining budget amount';
        $lines[] = '# TYPE locator_budget_remaining gauge';

        foreach ($row as $r) {
            $t = $this->label($r['tenantId'] ?? 't', $r['op'] ?? 'op');
            $remaining = (float)($r['remaining'] ?? 0.0);
            $used = (float)($r['used'] ?? 0.0);
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
