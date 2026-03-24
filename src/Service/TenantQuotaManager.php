<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class TenantQuotaManager implements TenantQuotaManagerInterface {
    /** @var array<string, array<string, array{limit:int, used:int}>> */
    private array $cfg = [];
    public function __construct(array $bootstrap=[
        'default' => ['geocode'=>['limit'=>100000,'used'=>0], 'reverse'=>['limit'=>50000,'used'=>0], 'batch'=>['limit'=>10000,'used'=>0]]
    ]) { foreach ($bootstrap as $t=>$m) { $this->cfg[$t]=$m; } }
    public function allow(string $tenantId, string $op, int $unit=1, bool $consume=true): bool {
        $tid = $tenantId !== '' ? $tenantId : 'default';
        $o = $this->cfg[$tid][$op] ?? ['limit'=>0,'used'=>0];
        $ok = ($o['used'] + max(0,$unit)) <= $o['limit'];
        if ($ok && $consume) {
            $o['used'] += $unit; $this->cfg[$tid][$op] = $o;
        }
        return $ok;
    }
    public function remaining(string $tenantId, string $op): int {
        $tid = $tenantId !== '' ? $tenantId : 'default';
        $o = $this->cfg[$tid][$op] ?? ['limit'=>0,'used'=>0];
        return max(0, $o['limit'] - $o['used']);
    }
}
