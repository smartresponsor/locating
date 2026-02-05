<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class IpAuthzPolicy implements IpAuthzPolicyInterface {
    /** @var array<string, array<int, array{action:string,cidr:string}>> */
    private array $rule = [];
    public function allow(string $tenantId, string $ip): bool {
        $rule = $this->rule[$tenantId] ?? [];
        ksort($rule, SORT_NUMERIC); // lower priority value first
        foreach ($rule as $prio => $r) {
            if ($this->match($ip, $r['cidr'])) {
                return $r['action'] === 'allow';
            }
        }
        return true; // default allow
    }
    public function add(string $tenantId, string $action, string $cidr, int $priority): void {
        $t = $this->rule[$tenantId] ?? [];
        $t[$priority] = ['action'=>$action==='deny'?'deny':'allow', 'cidr'=>$cidr];
        $this->rule[$tenantId] = $t;
    }
    private function match(string $ip, string $cidr): bool {
        // IPv4/IPv6 CIDR match
        try {
            if (!str_contains($cidr, '/')) {
                return $ip === $cidr;
            }
            [$net, $mask] = explode('/', $cidr, 2);
            $netw = @inet_pton($net);
            $addr = @inet_pton($ip);
            $mask = (int)$mask;
            if ($netw===false || $addr===false) { return false; }
            $len = strlen($netw);
            $bytes = intdiv($mask,8); $bits = $mask % 8;
            if ($len !== strlen($addr)) { return false; }
            if ($bytes>0 && substr($netw,0,$bytes)!==substr($addr,0,$bytes)) { return false; }
            if ($bits>0) {
                $n = ord($netw[$bytes]) >> (8-$bits);
                $a = ord($addr[$bytes]) >> (8-$bits);
                if ($n !== $a) { return false; }
            }
            return true;
        } catch (\Throwable) { return false; }
    }
}
