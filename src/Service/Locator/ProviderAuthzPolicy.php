<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class ProviderAuthzPolicy implements ProviderAuthzPolicyInterface {
    /** @var array<string, array<int, array{region:string,op:string,providerId:string,action:string}>> */
    private array $rule = [];
    public function allow(string $tenantId, string $region, string $op, string $providerId): bool {
        $rule = $this->rule[$tenantId] ?? [];
        ksort($rule, SORT_NUMERIC);
        foreach ($rule as $prio=>$r){
            if (($r['region']==='*'||$r['region']===$region) && ($r['op']==='*'||$r['op']===$op) &&
                ($r['providerId']==='*'||$r['providerId']===$providerId)) {
                return $r['action']==='allow';
            }
        }
        return true;
    }
    public function add(string $tenantId, string $region, string $op, string $providerId, string $action, int $priority): void {
        $t = $this->rule[$tenantId] ?? [];
        $t[$priority] = ['region'=>$region, 'op'=>$op, 'providerId'=>$providerId, 'action'=>$action==='deny'?'deny':'allow'];
        $this->rule[$tenantId] = $t;
    }
}
