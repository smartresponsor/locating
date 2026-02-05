<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class BudgetGuard implements BudgetGuardInterface {
    /** @var array<string, array<string, array{cap:float, used:float, day:string}>> */
    private array $m = [];
    private function today(): string { return gmdate('Y-m-d'); }
    private function resetIfNewDay(string $tenantId, string $op): void {
        $row = $this->m[$tenantId][$op] ?? null;
        if ($row !== null && $row['day'] !== $this->today()) {
            $this->m[$tenantId][$op]['used'] = 0.0;
            $this->m[$tenantId][$op]['day'] = $this->today();
        }
    }
    public function setCap(string $tenantId, string $op, float $cap): void {
        $this->m[$tenantId][$op] = ['cap'=>max(0.0,$cap),'used'=>0.0,'day'=>$this->today()];
    }
    public function stat(string $tenantId, string $op): array {
        $this->resetIfNewDay($tenantId, $op);
        $row = $this->m[$tenantId][$op] ?? ['cap'=>0.0,'used'=>0.0,'day'=>$this->today()];
        return [$row['cap'], $row['used']];
    }
    public function canSpend(string $tenantId, string $op, float $cost): bool {
        [$cap,$used] = $this->stat($tenantId, $op);
        return ($used + max(0.0,$cost)) <= $cap + 1e-9;
    }
    public function charge(string $tenantId, string $op, float $cost): bool {
        if (!$this->canSpend($tenantId, $op, $cost)) { return false; }
        $this->m[$tenantId][$op]['used'] += max(0.0,$cost);
        return true;
    }
}
